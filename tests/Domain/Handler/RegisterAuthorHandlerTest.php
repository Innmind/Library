<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterAuthorHandler,
    Command\RegisterAuthor,
    Repository\AuthorRepositoryInterface,
    Entity\Author,
    Entity\Author\IdentityInterface,
    Specification\Author\Name
};
use Innmind\Immutable\{
    Set,
    SetInterface
};

class RegisterAuthorHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new RegisterAuthorHandler(
            $repository = $this->createMock(AuthorRepositoryInterface::class)
        );
        $command = new RegisterAuthor(
            $this->createMock(IdentityInterface::class),
            'John Doe'
        );
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(Name $spec): bool {
                return $spec->value() === 'John Doe';
            }))
            ->willReturn(new Set(Author::class));
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(Author $author) use ($command): bool {
                return $author->identity() === $command->identity() &&
                    $author->name() === 'John Doe';
            }));

        $this->assertNull($handler($command));
    }

    /**
     * @expectedException Domain\Exception\AuthorAlreadyExistException
     */
    public function testThrowWhenAuthorAlreadyExist()
    {
        $handler = new RegisterAuthorHandler(
            $repository = $this->createMock(AuthorRepositoryInterface::class)
        );
        $command = new RegisterAuthor(
            $this->createMock(IdentityInterface::class),
            'John Doe'
        );
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(Name $spec): bool {
                return $spec->value() === 'John Doe';
            }))
            ->willReturn(
                $set = $this->createMock(SetInterface::class)
            );
        $set
            ->expects($this->once())
            ->method('size')
            ->willReturn(2);
        $repository
            ->expects($this->never())
            ->method('add');

        $this->assertNull($handler($command));
    }
}
