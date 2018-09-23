<?php
declare(strict_types = 1);

namespace Tests\Dommain\Event;

use Domain\{
    Entity\HtmlPage\Identity,
    Event\HtmlPageRegistered
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use PHPUnit\Framework\TestCase;

class HtmlPageRegisteredTest extends TestCase
{
    public function testInterface()
    {
        $event = new HtmlPageRegistered(
            $identity = $this->createMock(Identity::class),
            $path = $this->createMock(PathInterface::class),
            $query = $this->createMock(QueryInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($path, $event->path());
        $this->assertSame($query, $event->query());
    }
}
