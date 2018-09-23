<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HttpResource;

use Domain\{
    Handler\HttpResource\RegisterAuthorHandler,
    Command\HttpResource\RegisterAuthor,
    Repository\ResourceAuthorRepository,
    Entity\ResourceAuthor,
    Entity\ResourceAuthor\Identity,
    Entity\Author\Identity as AuthorIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\ResourceAuthorRegistered
};
use Innmind\TimeContinuum\{
    TimeContinuumInterface,
    PointInTimeInterface
};
use PHPUnit\Framework\TestCase;

class RegisterAuthorHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new RegisterAuthorHandler(
            $repository = $this->createMock(ResourceAuthorRepository::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterAuthor(
            $this->createMock(Identity::class),
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
