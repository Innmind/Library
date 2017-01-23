<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\AuthorRegistered,
    Entity\Author\IdentityInterface,
    Entity\Author\Name
};

class AuthorRegisteredTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new AuthorRegistered(
            $identity = $this->createMock(IdentityInterface::class),
            $name = new Name('John Doe')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($name, $event->name());
    }
}
