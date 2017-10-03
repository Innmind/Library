<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Domain,
    Entity\Domain\Identity,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain,
    Event\DomainRegistered
};
use Innmind\EventBus\ContainsRecordedEventsInterface;
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

        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $domain);
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
            $domain->recordedEvents()->current()
        );
        $this->assertSame(
            $identity,
            $domain->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $name,
            $domain->recordedEvents()->current()->name()
        );
        $this->assertSame(
            $tld,
            $domain->recordedEvents()->current()->tld()
        );
    }
}
