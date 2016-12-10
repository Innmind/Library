<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\AlternateCreated,
    Entity\Alternate\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Model\Language
};

class AlternateCreatedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new AlternateCreated(
            $identity = $this->createMock(IdentityInterface::class),
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
