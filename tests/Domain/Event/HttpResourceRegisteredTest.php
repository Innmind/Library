<?php
declare(strict_types = 1);

namespace Tests\Dommain\Event;

use Domain\{
    Entity\HttpResource\Identity,
    Event\HttpResourceRegistered
};
use Innmind\Url\{
    Path,
    Query
};
use PHPUnit\Framework\TestCase;

class HttpResourceRegisteredTest extends TestCase
{
    public function testInterface()
    {
        $event = new HttpResourceRegistered(
            $identity = $this->createMock(Identity::class),
            $path = Path::none(),
            $query = Query::none()
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($path, $event->path());
        $this->assertSame($query, $event->query());
    }
}
