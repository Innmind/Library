<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\DomainHost,
    Entity\DomainHost\Identity,
    Entity\Domain\Identity as DomainIdentity,
    Entity\Host\Identity as HostIdentity,
    Event\DomainHostCreated,
};
use Innmind\TimeContinuum\PointInTime;
use Innmind\EventBus\ContainsRecordedEvents;
use PHPUnit\Framework\TestCase;

class DomainHostTest extends TestCase
{
    public function testInstanciation()
    {
        $entity = new DomainHost(
            $identity = $this->createMock(Identity::class),
            $domain = $this->createMock(DomainIdentity::class),
            $host = $this->createMock(HostIdentity::class),
            $foundAt = $this->createMock(PointInTime::class)
        );

        $this->assertInstanceOf(ContainsRecordedEvents::class, $entity);
        $this->assertSame($identity, $entity->identity());
        $this->assertSame($domain, $entity->domain());
        $this->assertSame($host, $entity->host());
        $this->assertSame($foundAt, $entity->foundAt());
        $this->assertCount(0, $entity->recordedEvents());
    }

    public function testCreate()
    {
        $entity = DomainHost::create(
            $identity = $this->createMock(Identity::class),
            $domain = $this->createMock(DomainIdentity::class),
            $host = $this->createMock(HostIdentity::class),
            $foundAt = $this->createMock(PointInTime::class)
        );

        $this->assertInstanceOf(DomainHost::class, $entity);
        $this->assertCount(1, $entity->recordedEvents());
        $this->assertInstanceOf(
            DomainHostCreated::class,
            $entity->recordedEvents()->first()
        );
        $this->assertSame(
            $identity,
            $entity->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $domain,
            $entity->recordedEvents()->first()->domain()
        );
        $this->assertSame(
            $host,
            $entity->recordedEvents()->first()->host()
        );
        $this->assertSame(
            $foundAt,
            $entity->recordedEvents()->first()->foundAt()
        );
    }
}
