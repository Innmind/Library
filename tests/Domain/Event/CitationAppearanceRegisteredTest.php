<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\CitationAppearanceRegistered,
    Entity\CitationAppearance\IdentityInterface,
    Entity\Citation\IdentityInterface as CitationIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

class CitationAppearanceRegisteredTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new CitationAppearanceRegistered(
            $identity = $this->createMock(IdentityInterface::class),
            $citation = $this->createMock(CitationIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $foundAt = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($citation, $event->citation());
        $this->assertSame($resource, $event->resource());
        $this->assertSame($foundAt, $event->foundAt());
    }
}
