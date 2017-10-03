<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\ResourceAuthorRegistered,
    Entity\ResourceAuthor\Identity,
    Entity\Author\Identity as AuthorIdentity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;
use PHPUnit\Framework\TestCase;

class ResourceAuthorRegisteredTest extends TestCase
{
    public function testInterface()
    {
        $event = new ResourceAuthorRegistered(
            $identity = $this->createMock(Identity::class),
            $author = $this->createMock(AuthorIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $asOf = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($author, $event->author());
        $this->assertSame($resource, $event->resource());
        $this->assertSame($asOf, $event->asOf());
    }
}
