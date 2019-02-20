<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Reference,
    Entity\Reference\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\ReferenceCreated,
};
use Innmind\EventBus\ContainsRecordedEvents;
use PHPUnit\Framework\TestCase;

class ReferenceTest extends TestCase
{
    public function testInstanciation()
    {
        $entity = new Reference(
            $identity = $this->createMock(Identity::class),
            $source = $this->createMock(ResourceIdentity::class),
            $target = $this->createMock(ResourceIdentity::class)
        );

        $this->assertInstanceOf(ContainsRecordedEvents::class, $entity);
        $this->assertSame($identity, $entity->identity());
        $this->assertSame($source, $entity->source());
        $this->assertSame($target, $entity->target());
        $this->assertCount(0, $entity->recordedEvents());
    }

    public function testCreate()
    {
        $entity = Reference::create(
            $identity = $this->createMock(Identity::class),
            $source = $this->createMock(ResourceIdentity::class),
            $target = $this->createMock(ResourceIdentity::class)
        );

        $this->assertInstanceOf(Reference::class, $entity);
        $this->assertCount(1, $entity->recordedEvents());
        $this->assertInstanceOf(
            ReferenceCreated::class,
            $entity->recordedEvents()->current()
        );
        $this->assertSame(
            $identity,
            $entity->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $source,
            $entity->recordedEvents()->current()->source()
        );
        $this->assertSame(
            $target,
            $entity->recordedEvents()->current()->target()
        );
    }
}
