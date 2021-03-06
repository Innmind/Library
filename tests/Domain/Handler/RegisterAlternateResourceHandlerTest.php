<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterAlternateResourceHandler,
    Command\RegisterAlternateResource,
    Repository\AlternateRepository,
    Entity\Alternate,
    Entity\Alternate\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\AlternateCreated,
    Specification\AndSpecification,
    Specification\Alternate\HttpResource,
    Specification\Alternate\Alternate as AlternateSpec,
    Specification\Alternate\Language,
    Model\Language as Model,
    Exception\AlternateAlreadyExist,
};
use Innmind\Immutable\{
    Set,
    SetInterface,
};
use PHPUnit\Framework\TestCase;

class RegisterAlternateResourceHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new RegisterAlternateResourceHandler(
            $repository = $this->createMock(AlternateRepository::class)
        );
        $command = new RegisterAlternateResource(
            $this->createMock(Identity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class),
            new Model('fr')
        );
        $command
            ->resource()
            ->expects($this->once())
            ->method('toString')
            ->willReturn('resource uuid');
        $command
            ->alternate()
            ->expects($this->once())
            ->method('toString')
            ->willReturn('alternate uuid');
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof AndSpecification &&
                    $spec->left()->left() instanceof HttpResource &&
                    $spec->left()->right() instanceof AlternateSpec &&
                    $spec->right() instanceof Language &&
                    $spec->left()->left()->value() === 'resource uuid' &&
                    $spec->left()->right()->value() === 'alternate uuid' &&
                    $spec->right()->value() === 'fr';
            }))
            ->willReturn(Set::of(Alternate::class));
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(Alternate $alternate) use ($command): bool {
                return $alternate->identity() === $command->identity() &&
                    $alternate->resource() === $command->resource() &&
                    $alternate->alternate() === $command->alternate() &&
                    $alternate->language() === $command->language() &&
                    $alternate->recordedEvents()->size() === 1 &&
                    $alternate->recordedEvents()->first() instanceof AlternateCreated;
            }));

        $this->assertNull($handler($command));
    }

    public function testThrowWhenAlternateAlreadyExist()
    {
        $handler = new RegisterAlternateResourceHandler(
            $repository = $this->createMock(AlternateRepository::class)
        );
        $command = new RegisterAlternateResource(
            $this->createMock(Identity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class),
            new Model('fr')
        );
        $command
            ->resource()
            ->expects($this->once())
            ->method('toString')
            ->willReturn('resource uuid');
        $command
            ->alternate()
            ->expects($this->once())
            ->method('toString')
            ->willReturn('alternate uuid');
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof AndSpecification &&
                    $spec->left()->left() instanceof HttpResource &&
                    $spec->left()->right() instanceof AlternateSpec &&
                    $spec->right() instanceof Language &&
                    $spec->left()->left()->value() === 'resource uuid' &&
                    $spec->left()->right()->value() === 'alternate uuid' &&
                    $spec->right()->value() === 'fr';
            }))
            ->willReturn(
                Set::of(
                    Alternate::class,
                    new Alternate(
                        $this->createMock(Identity::class),
                        $this->createMock(ResourceIdentity::class),
                        $this->createMock(ResourceIdentity::class),
                        new Model('fr')
                    )
                )
            );
        $repository
            ->expects($this->never())
            ->method('add');

        $this->expectException(AlternateAlreadyExist::class);

        $handler($command);
    }
}
