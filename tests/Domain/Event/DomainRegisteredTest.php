<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\DomainRegistered,
    Entity\Domain\IdentityInterface,
    Entity\Domain\Name
};

class DomainRegisteredTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new DomainRegistered(
            $identity = $this->createMock(IdentityInterface::class),
            $name = new Name('example'),
            'com'
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($name, $event->name());
        $this->assertSame('com', $event->tld());
    }
}
