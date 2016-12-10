<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterHttpResourceHandler,
    Command\RegisterHttpResource,
    Repository\HttpResourceRepositoryInterface,
    Repository\HostResourceRepositoryInterface,
    Entity\HttpResource,
    Entity\HostResource,
    Entity\Host,
    Entity\HttpResource\IdentityInterface,
    Entity\HostResource\IdentityInterface as RelationIdentity,
    Entity\Host\IdentityInterface as HostIdentity,
    Entity\Host\Name,
    Specification\AndSpecification,
    Specification\HttpResource\Path,
    Specification\HttpResource\Query,
    Specification\HostResource\InResources,
    Specification\HostResource\Host as HostSpec
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

class RegisterHttpResourceHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new RegisterHttpResourceHandler(
            $resourceRepository = $this->createMock(HttpResourceRepositoryInterface::class),
            $relationRepository = $this->createMock(HostResourceRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterHttpResource(
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
        $resourceRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Path &&
                    $spec->right() instanceof Query &&
                    $spec->left()->value() === '/path' &&
                    $spec->right()->value() === '?query';
            }))
            ->willReturn(new Set(HttpResource::class));
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
            $resourceRepository = $this->createMock(HttpResourceRepositoryInterface::class),
            $relationRepository = $this->createMock(HostResourceRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterHttpResource(
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
                (new Set(HttpResource::class))->add(
                    new HttpResource(
                        $identity = $this->createMock(IdentityInterface::class),
                        $this->createMock(PathInterface::class),
                        $this->createMock(QueryInterface::class)
                    )
                )
            );
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('resource uuid');
        $relationRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left()->value() === ['resource uuid'] &&
                    $spec->right()->value() === 'host uuid';
            }))
            ->willReturn(new Set(Host::class));
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

    /**
     * @expectedException Domain\Exception\ResourceAlreadyExistException
     */
    public function testThrowWhenResourceAlreadyExist()
    {
        $handler = new RegisterHttpResourceHandler(
            $resourceRepository = $this->createMock(HttpResourceRepositoryInterface::class),
            $relationRepository = $this->createMock(HostResourceRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterHttpResource(
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
                (new Set(HttpResource::class))->add(
                    new HttpResource(
                        $identity = $this->createMock(IdentityInterface::class),
                        $this->createMock(PathInterface::class),
                        $this->createMock(QueryInterface::class)
                    )
                )
            );
        $identity
            ->expects($this->once())
            ->method('__toString')
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
                (new Set(Host::class))->add(
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

        $handler($command);
    }
}
