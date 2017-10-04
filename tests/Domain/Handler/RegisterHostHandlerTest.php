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
    Event\DomainHostCreated
};
use Innmind\TimeContinuum\{
    TimeContinuumInterface,
    PointInTimeInterface
};
use Innmind\Url\Authority\Host;
use Innmind\Immutable\{
    Set,
    SetInterface
};
use PHPUnit\Framework\TestCase;

class RegisterHostHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new RegisterHostHandler(
            $hostRepository = $this->createMock(HostRepository::class),
            $domainHostRepository = $this->createMock(DomainHostRepository::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterHost(
            $this->createMock(Identity::class),
            $this->createMock(DomainIdentity::class),
            $this->createMock(RelationIdentity::class),
            new Host('www.example.com')
        );
        $hostRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(Name $spec): bool {
                return $spec->value() === 'www.example.com';
            }))
            ->willReturn(new Set(HostEntity::class));
        $clock
            ->expects($this->once())
            ->method('now')
            ->willReturn(
                $now = $this->createMock(PointInTimeInterface::class)
            );
        $hostRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(HostEntity $host) use ($command): bool {
                return $host->identity() === $command->identity() &&
                    (string) $host->name() === 'www.example.com' &&
                    $host->recordedEvents()->size() === 1 &&
                    $host->recordedEvents()->current() instanceof HostRegistered;
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
                    $relation->recordedEvents()->current() instanceof DomainHostCreated;
            }));

        $this->assertNull($handler($command));
    }

    /**
     * @expectedException Domain\Exception\HostAlreadyExist
     */
    public function testThrowWhenHostAlreadyExist()
    {
        $handler = new RegisterHostHandler(
            $hostRepository = $this->createMock(HostRepository::class),
            $domainHostRepository = $this->createMock(DomainHostRepository::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterHost(
            $this->createMock(Identity::class),
            $this->createMock(DomainIdentity::class),
            $this->createMock(RelationIdentity::class),
            new Host('www.example.com')
        );
        $hostRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(Name $spec): bool {
                return $spec->value() === 'www.example.com';
            }))
            ->willReturn(
                $set = $this->createMock(SetInterface::class)
            );
        $set
            ->expects($this->once())
            ->method('size')
            ->willReturn(2);
        $set
            ->expects($this->once())
            ->method('current')
            ->willReturn(
                new HostEntity(
                    $this->createMock(Identity::class),
                    new NameModel('example.com')
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

        $handler($command);
    }
}
