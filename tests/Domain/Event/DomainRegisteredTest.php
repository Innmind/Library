<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\DomainRegistered,
    Entity\Domain\Identity,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain
};
use PHPUnit\Framework\TestCase;

class DomainRegisteredTest extends TestCase
{
    public function testInterface()
    {
        $event = new DomainRegistered(
            $identity = $this->createMock(Identity::class),
            $name = new Name('example'),
            $tld = new TopLevelDomain('com')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($name, $event->name());
        $this->assertSame($tld, $event->tld());
    }
}
