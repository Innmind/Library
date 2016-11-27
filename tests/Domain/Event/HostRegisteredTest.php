<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\HostRegistered,
    Entity\Host\IdentityInterface
};

class HostRegisteredTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new HostRegistered(
            $identity = $this->createMock(IdentityInterface::class),
            'www.example.com'
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame('www.example.com', $event->name());
    }
}
