<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\CitationRegistered,
    Entity\Citation\IdentityInterface
};

class CitationRegisteredTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new CitationRegistered(
            $identity = $this->createMock(IdentityInterface::class),
            'foo'
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame('foo', $event->text());
    }
}
