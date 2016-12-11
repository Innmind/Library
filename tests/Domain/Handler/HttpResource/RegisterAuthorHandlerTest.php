<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HttpResource;

use Domain\{
    Handler\HttpResource\RegisterAuthorHandler,
    Command\HttpResource\RegisterAuthor,
    Repository\ResourceAuthorRepositoryInterface,
    Entity\ResourceAuthor,
    Entity\ResourceAuthor\IdentityInterface,
    Entity\Author\IdentityInterface as AuthorIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\ResourceAuthorRegistered
};
use Innmind\TimeContinuum\{
    TimeContinuumInterface,
    PointInTimeInterface
};

class RegisterAuthorHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new RegisterAuthorHandler(
            $repository = $this->createMock(ResourceAuthorRepositoryInterface::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterAuthor(
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
                    $entity->recordedEvents()->current() instanceof ResourceAuthorRegistered;
            }));

        $this->assertNull($handler($command));
    }
}
