<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\MakeCanonicalLinkHandler,
    Command\MakeCanonicalLink,
    Repository\CanonicalRepositoryInterface,
    Entity\Canonical,
    Entity\Canonical\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Specification\AndSpecification,
    Specification\Canonical\HttpResource,
    Specification\Canonical\Canonical as CanonicalSpec,
    Event\CanonicalCreated
};
use Innmind\TimeContinuum\{
    TimeContinuumInterface,
    PointInTimeInterface
};
use Innmind\Immutable\{
    Set,
    SetInterface
};

class MakeCanonicalLinkHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new MakeCanonicalLinkHandler(
            $repository = $this->createMock(CanonicalRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new MakeCanonicalLink(
            $this->createMock(IdentityInterface::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class)
        );
        $command
            ->canonical()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('canonical uuid');
        $command
            ->resource()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('resource uuid');
        $clock
            ->expects($this->once())
            ->method('now')
            ->willReturn(
                $now = $this->createMock(PointInTimeInterface::class)
            );
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof HttpResource &&
                    $spec->right() instanceof CanonicalSpec &&
                    $spec->left()->value() === 'resource uuid' &&
                    $spec->right()->value() === 'canonical uuid';
            }))
            ->willReturn(new Set(Canonical::class));
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(Canonical $canonical) use ($command, $now): bool {
                return $canonical->identity() === $command->identity() &&
                    $canonical->canonical() === $command->canonical() &&
                    $canonical->resource() === $command->resource() &&
                    $canonical->foundAt() === $now &&
                    $canonical->recordedEvents()->size() === 1 &&
                    $canonical->recordedEvents()->current() instanceof CanonicalCreated;
            }));

        $this->assertNull($handler($command));
    }

    /**
     * @expectedException Domain\Exception\CanonicalAlreadyExistException
     */
    public function testThrowWhenCanonicalLinkAlreadyExist()
    {
        $handler = new MakeCanonicalLinkHandler(
            $repository = $this->createMock(CanonicalRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new MakeCanonicalLink(
            $this->createMock(IdentityInterface::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class)
        );
        $command
            ->canonical()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('canonical uuid');
        $command
            ->resource()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('resource uuid');
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof HttpResource &&
                    $spec->right() instanceof CanonicalSpec &&
                    $spec->left()->value() === 'resource uuid' &&
                    $spec->right()->value() === 'canonical uuid';
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
                new Canonical(
                    $this->createMock(IdentityInterface::class),
                    $this->createMock(ResourceIdentity::class),
                    $this->createMock(ResourceIdentity::class),
                    $this->createMock(PointInTimeInterface::class)
                )
            );
        $repository
            ->expects($this->never())
            ->method('add');
        $clock
            ->expects($this->never())
            ->method('now');

        $handler($command);
    }
}
