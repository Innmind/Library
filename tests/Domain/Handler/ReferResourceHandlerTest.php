<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\ReferResourceHandler,
    Command\ReferResource,
    Repository\ReferenceRepository,
    Entity\Reference,
    Entity\Reference\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Specification\AndSpecification,
    Specification\Reference\Source,
    Specification\Reference\Target,
    Event\ReferenceCreated
};
use Innmind\Immutable\{
    Set,
    SetInterface
};
use PHPUnit\Framework\TestCase;

class ReferResourceHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new ReferResourceHandler(
            $repository = $this->createMock(ReferenceRepository::class)
        );
        $command = new ReferResource(
            $this->createMock(Identity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class)
        );
        $command
            ->source()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('source uuid');
        $command
            ->target()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('target uuid');
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Source &&
                    $spec->right() instanceof Target &&
                    $spec->left()->value() === 'source uuid' &&
                    $spec->right()->value() === 'target uuid';
            }))
            ->willReturn(new Set(Reference::class));
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(Reference $ref) use ($command): bool {
                return $ref->identity() === $command->identity() &&
                    $ref->source() === $command->source() &&
                    $ref->target() === $command->target() &&
                    $ref->recordedEvents()->size() === 1 &&
                    $ref->recordedEvents()->current() instanceof ReferenceCreated;
            }));

        $this->assertNull($handler($command));
    }

    /**
     * @expectedException Domain\Exception\ReferenceAlreadyExist
     */
    public function testThrowWhenReferenceAlreadyExist()
    {
        $handler = new ReferResourceHandler(
            $repository = $this->createMock(ReferenceRepository::class)
        );
        $command = new ReferResource(
            $this->createMock(Identity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class)
        );
        $command
            ->source()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('source uuid');
        $command
            ->target()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('target uuid');
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Source &&
                    $spec->right() instanceof Target &&
                    $spec->left()->value() === 'source uuid' &&
                    $spec->right()->value() === 'target uuid';
            }))
            ->willReturn(
                $set = $this->createMock(SetInterface::class)
            );
        $set
            ->expects($this->once())
            ->method('size')
            ->willReturn(1);
        $set
            ->expects($this->once())
            ->method('current')
            ->willReturn(
                new Reference(
                    $this->createMock(Identity::class),
                    $this->createMock(ResourceIdentity::class),
                    $this->createMock(ResourceIdentity::class)
                )
            );
        $repository
            ->expects($this->never())
            ->method('add');

        $handler($command);
    }
}
