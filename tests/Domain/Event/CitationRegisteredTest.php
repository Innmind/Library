<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\CitationRegistered,
    Entity\Citation\Identity,
    Entity\Citation\Text
};
use PHPUnit\Framework\TestCase;

class CitationRegisteredTest extends TestCase
{
    public function testInterface()
    {
        $event = new CitationRegistered(
            $identity = $this->createMock(Identity::class),
            $text = new Text('foo')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($text, $event->text());
    }
}
