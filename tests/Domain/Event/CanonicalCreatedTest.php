<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\CanonicalCreated,
    Entity\Canonical\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};

class CanonicalCreatedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new CanonicalCreated(
            $identity = $this->createMock(IdentityInterface::class),
            $canonical = $this->createMock(ResourceIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($canonical, $event->canonical());
        $this->assertSame($resource, $event->resource());
    }
}
