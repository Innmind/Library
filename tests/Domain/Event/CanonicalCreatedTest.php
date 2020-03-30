<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\CanonicalCreated,
    Entity\Canonical\Identity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTime;
use PHPUnit\Framework\TestCase;

class CanonicalCreatedTest extends TestCase
{
    public function testInterface()
    {
        $event = new CanonicalCreated(
            $identity = $this->createMock(Identity::class),
            $canonical = $this->createMock(ResourceIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $foundAt = $this->createMock(PointInTime::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($canonical, $event->canonical());
        $this->assertSame($resource, $event->resource());
        $this->assertSame($foundAt, $event->foundAt());
    }
}
