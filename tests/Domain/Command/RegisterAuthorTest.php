<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\RegisterAuthor,
    Entity\Author\IdentityInterface,
    Entity\Author\Name
};

class RegisterAuthorTest extends \PHPUnit_Framework_TestCase
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
