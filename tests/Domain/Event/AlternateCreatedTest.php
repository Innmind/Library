<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\AlternateCreated,
    Entity\Alternate\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Model\Language
};
use PHPUnit\Framework\TestCase;

class AlternateCreatedTest extends TestCase
{
    public function testInterface()
    {
        $event = new AlternateCreated(
            $identity = $this->createMock(Identity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $alternate = $this->createMock(ResourceIdentity::class),
            $language = new Language('fr')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($resource, $event->resource());
        $this->assertSame($alternate, $event->alternate());
        $this->assertSame($language, $event->language());
    }
}
