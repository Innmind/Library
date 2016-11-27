<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HttpResource;

use Domain\{
    Event\HttpResource\CharsetSpecified,
    Entity\HttpResource\IdentityInterface
};

class CharsetSpecifiedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new CharsetSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            'utf-8'
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame('utf-8', $event->charset());
    }
}
