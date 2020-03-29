<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterHostHandler,
    Command\RegisterHost,
    Repository\HostRepository,
    Repository\DomainHostRepository,
    Entity\Host\Identity,
    Entity\Host\Name as NameModel,
    Entity\Domain\Identity as DomainIdentity,
    Entity\DomainHost\Identity as RelationIdentity,
    Specification\Host\Name,
    Entity\Host as HostEntity,
    Entity\DomainHost,
    Event\HostRegistered,
    Event\DomainHostCreated,
    Exception\HostAlreadyExist,
};
use Innmind\TimeContinuum\{
    Clock,
    PointInTime,
};
use Innmind\Url\Authority\Host;
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class RegisterHostHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new RegisterHostHandler(
            $hostRepository = $this->createMock(HostRepository::class),
            $domainHostRepository = $this->createMock(DomainHostRepository::class),
            $clock = $this->createMock(Clock::class)
        );
        $command = new RegisterHost(
            $this->createMock(Identity::class),
            $this->createMock(DomainIdentity::class),
            $this->createMock(RelationIdentity::class),
            Host::of('www.example.com')
        );
        $hostRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(Name $spec): bool {
                return $spec->value() === 'www.example.com';
            }))
            ->willReturn(Set::of(HostEntity::class));
        $clock
            ->expects($this->once())
            ->method('now')
            ->willReturn(
                $now = $this->createMock(PointInTime::class)
            );
        $hostRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(HostEntity $host) use ($command): bool {
                return $host->identity() === $command->identity() &&
                    (string) $host->name() === 'www.example.com' &&
                    $host->recordedEvents()->size() === 1 &&
                    $host->recordedEvents()->first() instanceof HostRegistered;
            }));
        $domainHostRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(DomainHost $relation) use ($command, $now): bool {
                return $relation->identity() === $command->relation() &&
                    $relation->domain() === $command->domain() &&
                    $relation->host() === $command->identity() &&
                    $relation->foundAt() === $now &&
                    $relation->recordedEvents()->size() === 1 &&
                    $relation->recordedEvents()->first() instanceof DomainHostCreated;
            }));

        $this->assertNull($handler($command));
    }

    public function testThrowWhenHostAlreadyExist()
    {
        $handler = new RegisterHostHandler(
            $hostRepository = $this->createMock(HostRepository::class),
            $domainHostRepository = $this->createMock(DomainHostRepository::class),
            $clock = $this->createMock(Clock::class)
        );
        $command = new RegisterHost(
            $this->createMock(Identity::class),
            $this->createMock(DomainIdentity::class),
            $this->createMock(RelationIdentity::class),
            Host::of('www.example.com')
        );
        $hostRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(Name $spec): bool {
                return $spec->value() === 'www.example.com';
            }))
            ->willReturn(
                Set::of(
                    HostEntity::class,
                    new HostEntity(
                        $this->createMock(Identity::class),
                        new NameModel('example.com')
                    )
                )
            );
        $clock
            ->expects($this->never())
            ->method('now');
        $hostRepository
            ->expects($this->never())
            ->method('add');
        $domainHostRepository
            ->expects($this->never())
            ->method('add');

        $this->expectException(HostAlreadyExist::class);

        $handler($command);
    }
}
