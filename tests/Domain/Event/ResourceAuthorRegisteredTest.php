<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\ResourceAuthorRegistered,
    Entity\ResourceAuthor\IdentityInterface,
    Entity\Author\IdentityInterface as AuthorIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

class ResourceAuthorRegisteredTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new ResourceAuthorRegistered(
            $identity = $this->createMock(IdentityInterface::class),
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
