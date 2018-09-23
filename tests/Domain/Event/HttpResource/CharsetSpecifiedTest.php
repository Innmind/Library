<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HttpResource;

use Domain\{
    Event\HttpResource\CharsetSpecified,
    Entity\HttpResource\Identity,
    Entity\HttpResource\Charset
};
use PHPUnit\Framework\TestCase;

class CharsetSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new CharsetSpecified(
            $identity = $this->createMock(Identity::class),
            $charset = new Charset('utf-8')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($charset, $event->charset());
    }
}
