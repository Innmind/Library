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
    Entity\Host\IdentityInterface as HostIdentity
};
use Innmind\TimeContinuum\{
    TimeContinuumInterface,
    PointInTimeInterface
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

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

        $clock
            ->expects($this->once())
            ->method('now')
            ->willReturn(
                $now = $this->createMock(PointInTimeInterface::class)
            );
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
}
