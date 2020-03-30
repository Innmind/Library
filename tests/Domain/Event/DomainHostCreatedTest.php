<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\DomainHostCreated,
    Entity\DomainHost\Identity,
    Entity\Domain\Identity as DomainIdentity,
    Entity\Host\Identity as HostIdentity
};
use Innmind\TimeContinuum\PointInTime;
use PHPUnit\Framework\TestCase;

class DomainHostCreatedTest extends TestCase
{
    public function testInterface()
    {
        $event = new DomainHostCreated(
            $identity = $this->createMock(Identity::class),
            $domain = $this->createMock(DomainIdentity::class),
            $host = $this->createMock(HostIdentity::class),
            $foundAt = $this->createMock(PointInTime::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($domain, $event->domain());
        $this->assertSame($host, $event->host());
        $this->assertSame($foundAt, $event->foundAt());
    }
}
