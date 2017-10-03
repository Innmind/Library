<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\DomainHost,
    Entity\DomainHost\Identity,
    Entity\Domain\Identity as DomainIdentity,
    Entity\Host\Identity as HostIdentity,
    Event\DomainHostCreated
};
use Innmind\TimeContinuum\PointInTimeInterface;
use Innmind\EventBus\ContainsRecordedEventsInterface;
use PHPUnit\Framework\TestCase;

class DomainHostTest extends TestCase
{
    public function testInstanciation()
    {
        $entity = new DomainHost(
            $identity = $this->createMock(Identity::class),
            $domain = $this->createMock(DomainIdentity::class),
            $host = $this->createMock(HostIdentity::class),
            $foundAt = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $entity);
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
            $foundAt = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertInstanceOf(DomainHost::class, $entity);
        $this->assertCount(1, $entity->recordedEvents());
        $this->assertInstanceOf(
            DomainHostCreated::class,
            $entity->recordedEvents()->current()
        );
        $this->assertSame(
            $identity,
            $entity->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $domain,
            $entity->recordedEvents()->current()->domain()
        );
        $this->assertSame(
            $host,
            $entity->recordedEvents()->current()->host()
        );
        $this->assertSame(
            $foundAt,
            $entity->recordedEvents()->current()->foundAt()
        );
    }
}
