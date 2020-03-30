<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Canonical,
    Entity\Canonical\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\CanonicalCreated,
};
use Innmind\EventBus\ContainsRecordedEvents;
use Innmind\TimeContinuum\PointInTime;
use PHPUnit\Framework\TestCase;

class CanonicalTest extends TestCase
{
    public function testInstanciation()
    {
        $entity = new Canonical(
            $identity = $this->createMock(Identity::class),
            $canonical = $this->createMock(ResourceIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $foundAt = $this->createMock(PointInTime::class)
        );

        $this->assertInstanceOf(ContainsRecordedEvents::class, $entity);
        $this->assertSame($identity, $entity->identity());
        $this->assertSame($canonical, $entity->canonical());
        $this->assertSame($resource, $entity->resource());
        $this->assertSame($foundAt, $entity->foundAt());
        $this->assertCount(0, $entity->recordedEvents());
    }

    public function testCreate()
    {
        $entity = Canonical::create(
            $identity = $this->createMock(Identity::class),
            $canonical = $this->createMock(ResourceIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $foundAt = $this->createMock(PointInTime::class)
        );

        $this->assertInstanceOf(Canonical::class, $entity);
        $this->assertCount(1, $entity->recordedEvents());
        $this->assertInstanceOf(
            CanonicalCreated::class,
            $entity->recordedEvents()->first()
        );
        $this->assertSame(
            $identity,
            $entity->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $canonical,
            $entity->recordedEvents()->first()->canonical()
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
