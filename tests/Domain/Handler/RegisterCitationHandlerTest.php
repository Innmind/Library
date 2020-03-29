<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterCitationHandler,
    Command\RegisterCitation,
    Repository\CitationRepository,
    Entity\Citation,
    Entity\Citation\Identity,
    Entity\Citation\Text as Model,
    Specification\Citation\Text,
    Event\CitationRegistered,
    Exception\CitationAlreadyExist,
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class RegisterCitationHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new RegisterCitationHandler(
            $repository = $this->createMock(CitationRepository::class)
        );
        $command = new RegisterCitation(
            $this->createMock(Identity::class),
            new Model('foo')
        );
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(Text $spec): bool {
                return $spec->value() === 'foo';
            }))
            ->willReturn(Set::of(Citation::class));
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(Citation $citation) use ($command): bool {
                return $citation->identity() === $command->identity() &&
                    $citation->text() === $command->text() &&
                    $citation->recordedEvents()->size() === 1 &&
                    $citation->recordedEvents()->first() instanceof CitationRegistered;
            }));

        $this->assertNull($handler($command));
    }

    public function testThrowWhenCitationAlreadyExist()
    {
        $handler = new RegisterCitationHandler(
            $repository = $this->createMock(CitationRepository::class)
        );
        $command = new RegisterCitation(
            $this->createMock(Identity::class),
            new Model('foo')
        );
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(Text $spec): bool {
                return $spec->value() === 'foo';
            }))
            ->willReturn(
                Set::of(
                    Citation::class,
                    new Citation(
                        $this->createMock(Identity::class),
                        new Model('foo')
                    )
                )
            );
        $repository
            ->expects($this->never())
            ->method('add');

        $this->expectException(CitationAlreadyExist::class);

        $handler($command);
    }
}
