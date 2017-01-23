<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Canonical,
    Entity\Canonical\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\CanonicalCreated
};
use Innmind\EventBus\ContainsRecordedEventsInterface;

class CanonicalTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanciation()
    {
        $entity = new Canonical(
            $identity = $this->createMock(IdentityInterface::class),
            $canonical = $this->createMock(ResourceIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class)
        );

        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $entity);
        $this->assertSame($identity, $entity->identity());
        $this->assertSame($canonical, $entity->canonical());
        $this->assertSame($resource, $entity->resource());
        $this->assertCount(0, $entity->recordedEvents());
    }

    public function testCreate()
    {
        $entity = Canonical::create(
            $identity = $this->createMock(IdentityInterface::class),
            $canonical = $this->createMock(ResourceIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class)
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
    }
}