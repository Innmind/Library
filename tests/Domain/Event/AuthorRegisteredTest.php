<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\AuthorRegistered,
    Entity\Author\IdentityInterface
};

class AuthorRegisteredTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new AuthorRegistered(
            $identity = $this->createMock(IdentityInterface::class),
            'John Doe'
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame('John Doe', $event->name());
    }
}
