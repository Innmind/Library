<?php
declare(strict_types = 1);

namespace Tests\Dommain\Event;

use Domain\{
    Entity\HtmlPage\Identity,
    Event\HtmlPageRegistered
};
use Innmind\Url\{
    Path,
    Query
};
use PHPUnit\Framework\TestCase;

class HtmlPageRegisteredTest extends TestCase
{
    public function testInterface()
    {
        $event = new HtmlPageRegistered(
            $identity = $this->createMock(Identity::class),
            $path = Path::none(),
            $query = Query::none()
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($path, $event->path());
        $this->assertSame($query, $event->query());
    }
}
