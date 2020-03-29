<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\CitationAppearance,
    Entity\CitationAppearance\Identity,
    Entity\Citation\Identity as CitationIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\CitationAppearanceRegistered,
};
use Innmind\TimeContinuum\PointInTime;
use Innmind\EventBus\ContainsRecordedEvents;
use PHPUnit\Framework\TestCase;

class CitationAppearanceTest extends TestCase
{
    public function testInstanciation()
    {
        $entity = new CitationAppearance(
            $identity = $this->createMock(Identity::class),
            $citation = $this->createMock(CitationIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $foundAt = $this->createMock(PointInTime::class)
        );

        $this->assertInstanceOf(ContainsRecordedEvents::class, $entity);
        $this->assertSame($identity, $entity->identity());
        $this->assertSame($citation, $entity->citation());
        $this->assertSame($resource, $entity->resource());
        $this->assertSame($foundAt, $entity->foundAt());
        $this->assertCount(0, $entity->recordedEvents());
    }

    public function testRegister()
    {
        $entity = CitationAppearance::register(
            $identity = $this->createMock(Identity::class),
            $citation = $this->createMock(CitationIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $foundAt = $this->createMock(PointInTime::class)
        );

        $this->assertInstanceOf(CitationAppearance::class, $entity);
        $this->assertCount(1, $entity->recordedEvents());
        $this->assertInstanceOf(
            CitationAppearanceRegistered::class,
            $entity->recordedEvents()->first()
        );
        $this->assertSame(
            $identity,
            $entity->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $citation,
            $entity->recordedEvents()->first()->citation()
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
