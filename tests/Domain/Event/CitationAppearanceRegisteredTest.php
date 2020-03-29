<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\CitationAppearanceRegistered,
    Entity\CitationAppearance\Identity,
    Entity\Citation\Identity as CitationIdentity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTime;
use PHPUnit\Framework\TestCase;

class CitationAppearanceRegisteredTest extends TestCase
{
    public function testInterface()
    {
        $event = new CitationAppearanceRegistered(
            $identity = $this->createMock(Identity::class),
            $citation = $this->createMock(CitationIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $foundAt = $this->createMock(PointInTime::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($citation, $event->citation());
        $this->assertSame($resource, $event->resource());
        $this->assertSame($foundAt, $event->foundAt());
    }
}
