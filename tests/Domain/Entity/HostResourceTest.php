<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\HostResource,
    Entity\HostResource\Identity,
    Entity\Host\Identity as HostIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\HostResourceCreated,
};
use Innmind\TimeContinuum\PointInTime;
use Innmind\EventBus\ContainsRecordedEvents;
use PHPUnit\Framework\TestCase;

class HostResourceTest extends TestCase
{
    public function testInstanciation()
    {
        $entity = new HostResource(
            $identity = $this->createMock(Identity::class),
            $host = $this->createMock(HostIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $foundAt = $this->createMock(PointInTime::class)
        );

        $this->assertInstanceOf(ContainsRecordedEvents::class, $entity);
        $this->assertSame($identity, $entity->identity());
        $this->assertSame($host, $entity->host());
        $this->assertSame($resource, $entity->resource());
        $this->assertSame($foundAt, $entity->foundAt());
        $this->assertCount(0, $entity->recordedEvents());
    }

    public function testCreate()
    {
        $entity = HostResource::create(
            $identity = $this->createMock(Identity::class),
            $host = $this->createMock(HostIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $foundAt = $this->createMock(PointInTime::class)
        );

        $this->assertInstanceOf(HostResource::class, $entity);
        $this->assertCount(1, $entity->recordedEvents());
        $this->assertInstanceOf(
            HostResourceCreated::class,
            $entity->recordedEvents()->first()
        );
        $this->assertSame(
            $identity,
            $entity->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $host,
            $entity->recordedEvents()->first()->host()
        );
        $this->assertSame(
            $resource,
            $entity->recordedEvents()->first()->resource()
        );
        $this->assertSame(
            $foundAt,
            $entity->recordedEvents()->first()->foundAt()
        );
    }
}
