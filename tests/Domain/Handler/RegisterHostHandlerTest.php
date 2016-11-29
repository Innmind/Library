<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterHostHandler,
    Command\RegisterHost,
    Repository\HostRepositoryInterface,
    Repository\DomainHostRepositoryInterface,
    Entity\Host\IdentityInterface,
    Entity\Domain\IdentityInterface as DomainIdentity,
    Entity\DomainHost\IdentityInterface as RelationIdentity,
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

class RegisterHostHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new RegisterHostHandler(
            $hostRepository = $this->createMock(HostRepositoryInterface::class),
            $domainHostRepository = $this->createMock(DomainHostRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterHost(
            $this->createMock(IdentityInterface::class),
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
                    $host->name() === 'www.example.com' &&
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
     * @expectedException Domain\Exception\HostAlreadyExistException
     */
    public function testThrowWhenHostAlreadyExist()
    {
        $handler = new RegisterHostHandler(
            $hostRepository = $this->createMock(HostRepositoryInterface::class),
            $domainHostRepository = $this->createMock(DomainHostRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterHost(
            $this->createMock(IdentityInterface::class),
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
