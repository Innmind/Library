<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterAuthorHandler,
    Command\RegisterAuthor,
    Repository\AuthorRepository,
    Entity\Author,
    Entity\Author\Identity,
    Entity\Author\Name as Model,
    Specification\Author\Name
};
use Innmind\Immutable\{
    Set,
    SetInterface
};
use PHPUnit\Framework\TestCase;

class RegisterAuthorHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new RegisterAuthorHandler(
            $repository = $this->createMock(AuthorRepository::class)
        );
        $command = new RegisterAuthor(
            $this->createMock(Identity::class),
            new Model('John Doe')
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
                    $author->name() === $command->name();
            }));

        $this->assertNull($handler($command));
    }

    /**
     * @expectedException Domain\Exception\AuthorAlreadyExist
     */
    public function testThrowWhenAuthorAlreadyExist()
    {
        $handler = new RegisterAuthorHandler(
            $repository = $this->createMock(AuthorRepository::class)
        );
        $command = new RegisterAuthor(
            $this->createMock(Identity::class),
            new Model('John Doe')
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
            ->willReturn(1);
        $set
            ->expects($this->once())
            ->method('current')
            ->willReturn(
                new Author(
                    $this->createMock(Identity::class),
                    new Model('foo')
                )
            );
        $repository
            ->expects($this->never())
            ->method('add');

        $this->assertNull($handler($command));
    }
}
