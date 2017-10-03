<?php
declare(strict_types = 1);

use Domain\{
    Event\DomainHostCreated,
    Entity\DomainHost\Identity,
    Entity\Domain\Identity as DomainIdentity,
    Entity\Host\Identity as HostIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;
use PHPUnit\Framework\TestCase;

class DomainHostCreatedTest extends TestCase
{
    public function testInterface()
    {
        $event = new DomainHostCreated(
            $identity = $this->createMock(Identity::class),
            $domain = $this->createMock(DomainIdentity::class),
            $host = $this->createMock(HostIdentity::class),
            $foundAt = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($domain, $event->domain());
        $this->assertSame($host, $event->host());
        $this->assertSame($foundAt, $event->foundAt());
    }
}
