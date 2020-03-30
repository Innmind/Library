<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterImageHandler,
    Command\RegisterImage,
    Repository\ImageRepository,
    Repository\HostResourceRepository,
    Entity\Image,
    Entity\HostResource,
    Entity\Host,
    Entity\Image\Identity,
    Entity\HostResource\Identity as RelationIdentity,
    Entity\Host\Identity as HostIdentity,
    Entity\Host\Name,
    Specification\AndSpecification,
    Specification\HttpResource\Path,
    Specification\HttpResource\Query,
    Specification\HostResource\InResources,
    Specification\HostResource\Host as HostSpec,
    Event\ImageRegistered,
    Exception\ImageAlreadyExist,
};
use Innmind\TimeContinuum\{
    Clock,
    PointInTime,
};
use Innmind\Url\{
    Path as PathModel,
    Query as QueryModel,
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class RegisterImageHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new RegisterImageHandler(
            $imageRepository = $this->createMock(ImageRepository::class),
            $relationRepository = $this->createMock(HostResourceRepository::class),
            $clock = $this->createMock(Clock::class)
        );
        $command = new RegisterImage(
            $this->createMock(Identity::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(RelationIdentity::class),
            PathModel::of('/path'),
            QueryModel::of('?query')
        );

        $clock
            ->expects($this->once())
            ->method('now')
            ->willReturn(
                $now = $this->createMock(PointInTime::class)
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
            ->willReturn(Set::of(Image::class));
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
                    $image->recordedEvents()->first() instanceof ImageRegistered;
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
            $imageRepository = $this->createMock(ImageRepository::class),
            $relationRepository = $this->createMock(HostResourceRepository::class),
            $clock = $this->createMock(Clock::class)
        );
        $command = new RegisterImage(
            $this->createMock(Identity::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(RelationIdentity::class),
            PathModel::of('/path'),
            QueryModel::of('?query')
        );
        $command
            ->host()
            ->expects($this->once())
            ->method('toString')
            ->willReturn('host uuid');

        $clock
            ->expects($this->once())
            ->method('now')
            ->willReturn(
                $now = $this->createMock(PointInTime::class)
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
                Set::of(
                    Image::class,
                    new Image(
                        $identity = $this->createMock(Identity::class),
                        PathModel::none(),
                        QueryModel::none()
                    )
                )
            );
        $identity
            ->expects($this->once())
            ->method('toString')
            ->willReturn('image uuid');
        $relationRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left()->value() === ['image uuid'] &&
                    $spec->right()->value() === 'host uuid';
            }))
            ->willReturn(Set::of(Host::class));
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

    public function testThrowWhenResourceAlreadyExist()
    {
        $handler = new RegisterImageHandler(
            $imageRepository = $this->createMock(ImageRepository::class),
            $relationRepository = $this->createMock(HostResourceRepository::class),
            $clock = $this->createMock(Clock::class)
        );
        $command = new RegisterImage(
            $this->createMock(Identity::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(RelationIdentity::class),
            PathModel::of('/path'),
            QueryModel::of('?query')
        );
        $command
            ->host()
            ->expects($this->once())
            ->method('toString')
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
                Set::of(
                    Image::class,
                    new Image(
                        $identity = $this->createMock(Identity::class),
                        PathModel::none(),
                        QueryModel::none()
                    )
                )
            );
        $identity
            ->expects($this->once())
            ->method('toString')
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
                Set::of(
                    Host::class,
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

        $this->expectException(ImageAlreadyExist::class);

        $handler($command);
    }
}
