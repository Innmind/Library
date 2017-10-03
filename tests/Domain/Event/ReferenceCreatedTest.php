<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\ReferenceCreated,
    Entity\Reference\Identity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use PHPUnit\Framework\TestCase;

class ReferenceCreatedTest extends TestCase
{
    public function testInterface()
    {
        $event = new ReferenceCreated(
            $identity = $this->createMock(Identity::class),
            $source = $this->createMock(ResourceIdentity::class),
            $target = $this->createMock(ResourceIdentity::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($source, $event->source());
        $this->assertSame($target, $event->target());
    }
}
