<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\Citation;

use Domain\{
    Handler\Citation\RegisterAppearanceHandler,
    Command\Citation\RegisterAppearance,
    Repository\CitationAppearanceRepositoryInterface,
    Entity\CitationAppearance,
    Entity\CitationAppearance\IdentityInterface,
    Entity\Citation\IdentityInterface as CitationIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
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

class RegisterAppearanceHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new RegisterAppearanceHandler(
            $repository = $this->createMock(CitationAppearanceRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterAppearance(
            $this->createMock(IdentityInterface::class),
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
     * @expectedException Domain\Exception\CitationAppearanceAlreadyExistException
     */
    public function testThrowWhenAppearanceAlreadyRegistered()
    {
        $handler = new RegisterAppearanceHandler(
            $repository = $this->createMock(CitationAppearanceRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterAppearance(
            $this->createMock(IdentityInterface::class),
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
        $repository
            ->expects($this->never())
            ->method('add');

        $handler($command);
    }
}
