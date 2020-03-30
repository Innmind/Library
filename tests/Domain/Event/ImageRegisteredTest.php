<?php
declare(strict_types = 1);

namespace Tests\Dommain\Event;

use Domain\{
    Entity\Image\Identity,
    Event\ImageRegistered
};
use Innmind\Url\{
    Path,
    Query
};
use PHPUnit\Framework\TestCase;

class ImageRegisteredTest extends TestCase
{
    public function testInterface()
    {
        $event = new ImageRegistered(
            $identity = $this->createMock(Identity::class),
            $path = Path::none(),
            $query = Query::none()
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($path, $event->path());
        $this->assertSame($query, $event->query());
    }
}
