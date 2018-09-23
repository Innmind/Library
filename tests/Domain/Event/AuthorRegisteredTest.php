<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\AuthorRegistered,
    Entity\Author\Identity,
    Entity\Author\Name
};
use PHPUnit\Framework\TestCase;

class AuthorRegisteredTest extends TestCase
{
    public function testInterface()
    {
        $event = new AuthorRegistered(
            $identity = $this->createMock(Identity::class),
            $name = new Name('John Doe')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($name, $event->name());
    }
}
