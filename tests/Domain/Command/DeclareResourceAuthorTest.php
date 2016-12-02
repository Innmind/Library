<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\DeclareResourceAuthor,
    Entity\ResourceAuthor\IdentityInterface,
    Entity\Author\IdentityInterface as AuthorIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};

class DeclareResourceAuthorTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new DeclareResourceAuthor(
            $identity = $this->createMock(IdentityInterface::class),
            $author = $this->createMock(AuthorIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($author, $command->author());
        $this->assertSame($resource, $command->resource());
    }
}
