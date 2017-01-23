<?php
declare(strict_types = 1);

namespace Tests\Dommain\Event;

use Domain\{
    Entity\HttpResource\IdentityInterface,
    Event\HttpResourceRegistered
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

class HttpResourceRegisteredTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new HttpResourceRegistered(
            $identity = $this->createMock(IdentityInterface::class),
            $path = $this->createMock(PathInterface::class),
            $query = $this->createMock(QueryInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($path, $event->path());
        $this->assertSame($query, $event->query());
    }
}