<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\DeclareResourceAuthorHandler,
    Command\DeclareResourceAuthor,
    Repository\ResourceAuthorRepositoryInterface,
    Entity\ResourceAuthor,
    Entity\ResourceAuthor\IdentityInterface,
    Entity\Author\IdentityInterface as AuthorIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\ResourceAuthorDeclared
};
use Innmind\TimeContinuum\{
    TimeContinuumInterface,
    PointInTimeInterface
};

class DeclareResourceAuthorHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new DeclareResourceAuthorHandler(
            $repository = $this->createMock(ResourceAuthorRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new DeclareResourceAuthor(
            $this->createMock(IdentityInterface::class),
            $this->createMock(AuthorIdentity::class),
            $this->createMock(ResourceIdentity::class)
        );
        $clock
            ->expects($this->once())
            ->method('now')
            ->willReturn(
                $now = $this->createMock(PointInTimeInterface::class)
            );
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(ResourceAuthor $entity) use ($command, $now): bool {
                return $entity->identity() === $command->identity() &&
                    $entity->author() === $command->author() &&
                    $entity->resource() === $command->resource() &&
                    $entity->asOf() === $now &&
                    $entity->recordedEvents()->size() === 1 &&
                    $entity->recordedEvents()->current() instanceof ResourceAuthorDeclared;
            }));

        $this->assertNull($handler($command));
    }
}
