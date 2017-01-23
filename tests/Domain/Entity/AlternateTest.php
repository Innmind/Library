<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Alternate,
    Entity\Alternate\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\AlternateCreated,
    Model\Language
};
use Innmind\EventBus\ContainsRecordedEventsInterface;

class AlternateTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanciation()
    {
        $entity = new Alternate(
            $identity = $this->createMock(IdentityInterface::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $alternate = $this->createMock(ResourceIdentity::class),
            $language = new Language('fr')
        );

        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $entity);
        $this->assertSame($identity, $entity->identity());
        $this->assertSame($resource, $entity->resource());
        $this->assertSame($alternate, $entity->alternate());
        $this->assertSame($language, $entity->language());
        $this->assertCount(0, $entity->recordedEvents());
    }

    public function testCreate()
    {
        $entity = Alternate::create(
            $identity = $this->createMock(IdentityInterface::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $alternate = $this->createMock(ResourceIdentity::class),
            $language = new Language('fr')
        );

        $this->assertInstanceOf(Alternate::class, $entity);
        $this->assertCount(1, $entity->recordedEvents());
        $this->assertInstanceOf(
            AlternateCreated::class,
            $entity->recordedEvents()->current()
        );
        $this->assertSame(
            $identity,
            $entity->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $resource,
            $entity->recordedEvents()->current()->resource()
        );
        $this->assertSame(
            $alternate,
            $entity->recordedEvents()->current()->alternate()
        );
        $this->assertSame(
            $language,
            $entity->recordedEvents()->current()->language()
        );
    }
}