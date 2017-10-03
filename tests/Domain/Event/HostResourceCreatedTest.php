<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\HostResourceCreated,
    Entity\HostResource\Identity,
    Entity\Host\Identity as HostIdentity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;
use PHPUnit\Framework\TestCase;

class HostResourceCreatedTest extends TestCase
{
    public function testInterface()
    {
        $event = new HostResourceCreated(
            $identity = $this->createMock(Identity::class),
            $host = $this->createMock(HostIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $foundAt = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($host, $event->host());
        $this->assertSame($resource, $event->resource());
        $this->assertSame($foundAt, $event->foundAt());
    }
}
