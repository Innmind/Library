<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterHttpResourceHandler,
    Command\RegisterHttpResource,
    Repository\HttpResourceRepository,
    Repository\HostResourceRepository,
    Entity\HttpResource,
    Entity\HostResource,
    Entity\Host,
    Entity\HttpResource\Identity,
    Entity\HostResource\Identity as RelationIdentity,
    Entity\Host\Identity as HostIdentity,
    Entity\Host\Name,
    Specification\AndSpecification,
    Specification\HttpResource\Path,
    Specification\HttpResource\Query,
    Specification\HostResource\InResources,
    Specification\HostResource\Host as HostSpec,
    Exception\HttpResourceAlreadyExist,
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

class RegisterHttpResourceHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new RegisterHttpResourceHandler(
            $resourceRepository = $this->createMock(HttpResourceRepository::class),
            $relationRepository = $this->createMock(HostResourceRepository::class),
            $clock = $this->createMock(Clock::class)
        );
        $command = new RegisterHttpResource(
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
        $resourceRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Path &&
                    $spec->right() instanceof Query &&
                    $spec->left()->value() === '/path' &&
                    $spec->right()->value() === '?query';
            }))
            ->willReturn(Set::of(HttpResource::class));
        $relationRepository
            ->expects($this->never())
            ->method('matching');
        $resourceRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(HttpResource $resource) use ($command): bool {
                return $resource->identity() === $command->identity() &&
                    $resource->path() === $command->path() &&
                    $resource->query() === $command->query() &&
                    $resource->recordedEvents()->size() === 1;
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
        $handler = new RegisterHttpResourceHandler(
            $resourceRepository = $this->createMock(HttpResourceRepository::class),
            $relationRepository = $this->createMock(HostResourceRepository::class),
            $clock = $this->createMock(Clock::class)
        );
        $command = new RegisterHttpResource(
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
        $resourceRepository
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
                    HttpResource::class,
                    new HttpResource(
                        $identity = $this->createMock(Identity::class),
                        PathModel::none(),
                        QueryModel::none()
                    )
                )
            );
        $identity
            ->expects($this->once())
            ->method('toString')
            ->willReturn('resource uuid');
        $relationRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left()->value() === ['resource uuid'] &&
                    $spec->right()->value() === 'host uuid';
            }))
            ->willReturn(Set::of(Host::class));
        $resourceRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(HttpResource $resource) use ($command): bool {
                return $resource->identity() === $command->identity() &&
                    $resource->path() === $command->path() &&
                    $resource->query() === $command->query() &&
                    $resource->recordedEvents()->size() === 1;
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
        $handler = new RegisterHttpResourceHandler(
            $resourceRepository = $this->createMock(HttpResourceRepository::class),
            $relationRepository = $this->createMock(HostResourceRepository::class),
            $clock = $this->createMock(Clock::class)
        );
        $command = new RegisterHttpResource(
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
        $resourceRepository
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
                    HttpResource::class,
                    new HttpResource(
                        $identity = $this->createMock(Identity::class),
                        PathModel::none(),
                        QueryModel::none()
                    )
                )
            );
        $identity
            ->expects($this->once())
            ->method('toString')
            ->willReturn('resource uuid');
        $relationRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof InResources &&
                    $spec->right() instanceof HostSpec &&
                    $spec->left()->value() === ['resource uuid'] &&
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
        $resourceRepository
            ->expects($this->never())
            ->method('add');
        $relationRepository
            ->expects($this->never())
            ->method('add');

        $this->expectException(HttpResourceAlreadyExist::class);

        $handler($command);
    }
}
