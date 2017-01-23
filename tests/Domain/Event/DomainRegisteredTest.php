<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\DomainRegistered,
    Entity\Domain\IdentityInterface,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain
};

class DomainRegisteredTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new DomainRegistered(
            $identity = $this->createMock(IdentityInterface::class),
            $name = new Name('example'),
            $tld = new TopLevelDomain('com')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($name, $event->name());
        $this->assertSame($tld, $event->tld());
    }
}
