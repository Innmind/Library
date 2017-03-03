<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\RegisterAuthor,
    Entity\Author\IdentityInterface,
    Entity\Author\Name
};
use PHPUnit\Framework\TestCase;

class RegisterAuthorTest extends TestCase
{
    public function testInterface()
    {
        $command = new RegisterAuthor(
            $identity = $this->createMock(IdentityInterface::class),
            $name = new Name('John Doe')
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($name, $command->name());
    }
}
