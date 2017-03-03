<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterImageHandler,
    Command\RegisterImage,
    Repository\ImageRepositoryInterface,
    Repository\HostResourceRepositoryInterface,
    Entity\Image,
    Entity\HostResource,
    Entity\Host,
    Entity\Image\IdentityInterface,
    Entity\HostResource\IdentityInterface as RelationIdentity,
    Entity\Host\IdentityInterface as HostIdentity,
    Entity\Host\Name,
    Specification\AndSpecification,
    Specification\HttpResource\Path,
    Specification\HttpResource\Query,
    Specification\HostResource\InResources,
    Specification\HostResource\Host as HostSpec,
    Event\ImageRegistered
};
use Innmind\TimeContinuum\{
    TimeContinuumInterface,
    PointInTimeInterface
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class RegisterImageHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new RegisterImageHandler(
            $imageRepository = $this->createMock(ImageRepositoryInterface::class),
            $relationRepository = $this->createMock(HostResourceRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterImage(
            $this->createMock(IdentityInterface::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(RelationIdentity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
        $command
            ->path()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('/path');
        $command
            ->query()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('?query');

        $clock
            ->expects($this->once())
            ->method('now')
            ->willReturn(
                $now = $this->createMock(PointInTimeInterface::class)
            );
        $imageRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Path &&
                    $spec->right() instanceof Query &&
                    $spec->left()->value() === '/path' &&
                    $spec->right()->value() === '?query';
            }))
            ->willReturn(new Set(Image::class));
        $relationRepository
            ->expects($this->never())
            ->method('matching');
        $imageRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(Image $image) use ($command): bool {
                return $image->identity() === $command->identity() &&
                    $image->path() === $command->path() &&
                    $image->query() === $command->query() &&
                    $image->recordedEvents()->size() === 1 &&
                    $image->recordedEvents()->current() instanceof ImageRegistered;
            }));
        $relationRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(HostResource $relation) use ($command, $now): bool {
                return $relation->identity() === $command->relation() &&
                    $relation->host() === $command->host() &&
                    $relation->resource() === $command->identity() &&
                    $relation->foundAt() === $now;
            }));

        $this->assertNull($handler($command));
    }

    public function testLookForRelations()
    {
        $handler = new RegisterImageHandler(
            $imageRepository = $this->createMock(ImageRepositoryInterface::class),
            $relationRepository = $this->createMock(HostResourceRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterImage(
            $this->createMock(IdentityInterface::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(RelationIdentity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
        $command
            ->path()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('/path');
        $command
            ->query()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('?query');
        $command
            ->host()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('host uuid');

        $clock
            ->expects($this->once())
            ->method('now')
            ->willReturn(
                $now = $this->createMock(PointInTimeInterface::class)
            );
        $imageRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Path &&
                    $spec->right() instanceof Query &&
                    $spec->left()->value() === '/path' &&
                    $spec->right()->value() === '?query';
            }))
            ->willReturn(
                (new Set(Image::class))->add(
                    new Image(
                        $identity = $this->createMock(IdentityInterface::class),
                        $this->createMock(PathInterface::class),
                        $this->createMock(QueryInterface::class)
                    )
                )
            );
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('image uuid');
        $relationRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left()->value() === ['image uuid'] &&
                    $spec->right()->value() === 'host uuid';
            }))
            ->willReturn(new Set(Host::class));
        $imageRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(Image $image) use ($command): bool {
                return $image->identity() === $command->identity() &&
                    $image->path() === $command->path() &&
                    $image->query() === $command->query() &&
                    $image->recordedEvents()->size() === 1;
            }));
        $relationRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(HostResource $relation) use ($command, $now): bool {
                return $relation->identity() === $command->relation() &&
                    $relation->host() === $command->host() &&
                    $relation->resource() === $command->identity() &&
                    $relation->foundAt() === $now;
            }));

        $this->assertNull($handler($command));
    }

    /**
     * @expectedException Domain\Exception\ImageAlreadyExistException
     */
    public function testThrowWhenResourceAlreadyExist()
    {
        $handler = new RegisterImageHandler(
            $imageRepository = $this->createMock(ImageRepositoryInterface::class),
            $relationRepository = $this->createMock(HostResourceRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterImage(
            $this->createMock(IdentityInterface::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(RelationIdentity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
        $command
            ->path()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('/path');
        $command
            ->query()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('?query');
        $command
            ->host()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('host uuid');

        $clock
            ->expects($this->never())
            ->method('now');
        $imageRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Path &&
                    $spec->right() instanceof Query &&
                    $spec->left()->value() === '/path' &&
                    $spec->right()->value() === '?query';
            }))
            ->willReturn(
                (new Set(Image::class))->add(
                    new Image(
                        $identity = $this->createMock(IdentityInterface::class),
                        $this->createMock(PathInterface::class),
                        $this->createMock(QueryInterface::class)
                    )
                )
            );
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('image uuid');
        $relationRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof InResources &&
                    $spec->right() instanceof HostSpec &&
                    $spec->left()->value() === ['image uuid'] &&
                    $spec->right()->value() === 'host uuid';
            }))
            ->willReturn(
                (new Set(Host::class))->add(
                    new Host(
                        $this->createMock(HostIdentity::class),
                        new Name('some.domain.tld')
                    )
                )
            );
        $imageRepository
            ->expects($this->never())
            ->method('add');
        $relationRepository
            ->expects($this->never())
            ->method('add');

        $handler($command);
    }
}
