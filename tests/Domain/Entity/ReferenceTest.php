<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Reference,
    Entity\Reference\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\ReferenceCreated
};
use Innmind\EventBus\ContainsRecordedEventsInterface;

class ReferenceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanciation()
    {
        $entity = new Reference(
            $identity = $this->createMock(IdentityInterface::class),
            $source = $this->createMock(ResourceIdentity::class),
            $target = $this->createMock(ResourceIdentity::class)
        );

        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $entity);
        $this->assertSame($identity, $entity->identity());
        $this->assertSame($source, $entity->source());
        $this->assertSame($target, $entity->target());
        $this->assertCount(0, $entity->recordedEvents());
    }

    public function testCreate()
    {
        $entity = Reference::create(
            $identity = $this->createMock(IdentityInterface::class),
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
