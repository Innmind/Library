<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Canonical,
    Entity\Canonical\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\CanonicalCreated
};
use Innmind\EventBus\ContainsRecordedEventsInterface;
use Innmind\TimeContinuum\PointInTimeInterface;
use PHPUnit\Framework\TestCase;

class CanonicalTest extends TestCase
{
    public function testInstanciation()
    {
        $entity = new Canonical(
            $identity = $this->createMock(Identity::class),
            $canonical = $this->createMock(ResourceIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $foundAt = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $entity);
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
            $foundAt = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertInstanceOf(Canonical::class, $entity);
        $this->assertCount(1, $entity->recordedEvents());
        $this->assertInstanceOf(
            CanonicalCreated::class,
            $entity->recordedEvents()->current()
        );
        $this->assertSame(
            $identity,
            $entity->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $canonical,
            $entity->recordedEvents()->current()->canonical()
        );
        $this->assertSame(
            $resource,
            $entity->recordedEvents()->current()->resource()
        );
        $this->assertSame(
            $foundAt,
            $entity->recordedEvents()->current()->foundAt()
        );
    }
}
