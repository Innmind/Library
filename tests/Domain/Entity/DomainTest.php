<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Domain,
    Entity\Domain\Identity,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain,
    Event\DomainRegistered,
};
use Innmind\EventBus\ContainsRecordedEvents;
use PHPUnit\Framework\TestCase;

class DomainTest extends TestCase
{
    public function testInstanciation()
    {
        $domain = new Domain(
            $identity = $this->createMock(Identity::class),
            $name = new Name('example'),
            $tld = new TopLevelDomain('com')
        );

        $this->assertInstanceOf(ContainsRecordedEvents::class, $domain);
        $this->assertSame($identity, $domain->identity());
        $this->assertSame($name, $domain->name());
        $this->assertSame($tld, $domain->tld());
        $this->assertSame('example.com', (string) $domain);
        $this->assertCount(0, $domain->recordedEvents());
    }

    public function testRegister()
    {
        $domain = Domain::register(
            $identity = $this->createMock(Identity::class),
            $name = new Name('example'),
            $tld = new TopLevelDomain('com')
        );

        $this->assertInstanceOf(Domain::class, $domain);
        $this->assertCount(1, $domain->recordedEvents());
        $this->assertInstanceOf(
            DomainRegistered::class,
            $domain->recordedEvents()->first()
        );
        $this->assertSame(
            $identity,
            $domain->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $name,
            $domain->recordedEvents()->first()->name()
        );
        $this->assertSame(
            $tld,
            $domain->recordedEvents()->first()->tld()
        );
    }
}
