<?php
declare(strict_types = 1);

namespace Tests\Dommain\Event;

use Domain\{
    Entity\Image\IdentityInterface,
    Event\ImageRegistered
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

class ImageRegisteredTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new ImageRegistered(
            $identity = $this->createMock(IdentityInterface::class),
            $path = $this->createMock(PathInterface::class),
            $query = $this->createMock(QueryInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($path, $event->path());
        $this->assertSame($query, $event->query());
    }
}
