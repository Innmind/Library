<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HttpResource;

use Domain\{
    Command\HttpResource\RegisterAuthor,
    Entity\ResourceAuthor\Identity,
    Entity\Author\Identity as AuthorIdentity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use PHPUnit\Framework\TestCase;

class RegisterAuthorTest extends TestCase
{
    public function testInterface()
    {
        $command = new RegisterAuthor(
            $identity = $this->createMock(Identity::class),
            $author = $this->createMock(AuthorIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($author, $command->author());
        $this->assertSame($resource, $command->resource());
    }
}
