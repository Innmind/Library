<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\Citation;

use Domain\{
    Handler\Citation\RegisterAppearanceHandler,
    Command\Citation\RegisterAppearance,
    Repository\CitationAppearanceRepository,
    Entity\CitationAppearance,
    Entity\CitationAppearance\Identity,
    Entity\Citation\Identity as CitationIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Specification\AndSpecification,
    Specification\CitationAppearance\Citation,
    Specification\CitationAppearance\HttpResource,
    Event\CitationAppearanceRegistered
};
use Innmind\TimeContinuum\{
    TimeContinuumInterface,
    PointInTimeInterface
};
use Innmind\Immutable\{
    Set,
    SetInterface
};
use PHPUnit\Framework\TestCase;

class RegisterAppearanceHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new RegisterAppearanceHandler(
            $repository = $this->createMock(CitationAppearanceRepository::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterAppearance(
            $this->createMock(Identity::class),
            $this->createMock(CitationIdentity::class),
            $this->createMock(ResourceIdentity::class)
        );
        $command
            ->citation()
            ->expects($this->once())
            ->method('__toString')
            ->willreturn('citation uuid');
        $command
            ->resource()
            ->expects($this->once())
            ->method('__toString')
            ->willreturn('resource uuid');
        $clock
            ->expects($this->once())
            ->method('now')
            ->willReturn(
                $now = $this->createMock(PointInTimeInterface::class)
            );
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec) use ($command): bool {
                return $spec->left() instanceof Citation &&
                    $spec->right() instanceof HttpResource &&
                    $spec->left()->value() === 'citation uuid' &&
                    $spec->right()->value() === 'resource uuid';
            }))
            ->willReturn(new Set(CitationAppearance::class));
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(CitationAppearance $entity) use ($command, $now): bool {
                return $entity->identity() === $command->identity() &&
                    $entity->citation() === $command->citation() &&
                    $entity->resource() === $command->resource() &&
                    $entity->foundAt() === $now &&
                    $entity->recordedEvents()->size() === 1 &&
                    $entity->recordedEvents()->current() instanceof CitationAppearanceRegistered;
            }));

        $this->assertNull($handler($command));
    }

    /**
     * @expectedException Domain\Exception\CitationAppearanceAlreadyExist
     */
    public function testThrowWhenAppearanceAlreadyRegistered()
    {
        $handler = new RegisterAppearanceHandler(
            $repository = $this->createMock(CitationAppearanceRepository::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterAppearance(
            $this->createMock(Identity::class),
            $this->createMock(CitationIdentity::class),
            $this->createMock(ResourceIdentity::class)
        );
        $command
            ->citation()
            ->expects($this->once())
            ->method('__toString')
            ->willreturn('citation uuid');
        $command
            ->resource()
            ->expects($this->once())
            ->method('__toString')
            ->willreturn('resource uuid');
        $clock
            ->expects($this->never())
            ->method('now');
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec) use ($command): bool {
                return $spec->left() instanceof Citation &&
                    $spec->right() instanceof HttpResource &&
                    $spec->left()->value() === 'citation uuid' &&
                    $spec->right()->value() === 'resource uuid';
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
                new CitationAppearance(
                    $this->createMock(Identity::class),
                    $this->createMock(CitationIdentity::class),
                    $this->createMock(ResourceIdentity::class),
                    $this->createMock(PointInTimeInterface::class)
                )
            );
        $repository
            ->expects($this->never())
            ->method('add');

        $handler($command);
    }
}
