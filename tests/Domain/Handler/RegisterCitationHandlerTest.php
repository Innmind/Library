<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterCitationHandler,
    Command\RegisterCitation,
    Repository\CitationRepositoryInterface,
    Entity\Citation,
    Entity\Citation\IdentityInterface,
    Entity\Citation\Text as Model,
    Specification\Citation\Text,
    Event\CitationRegistered
};
use Innmind\Immutable\{
    Set,
    SetInterface
};

class RegisterCitationHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new RegisterCitationHandler(
            $repository = $this->createMock(CitationRepositoryInterface::class)
        );
        $command = new RegisterCitation(
            $this->createMock(IdentityInterface::class),
            new Model('foo')
        );
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(Text $spec): bool {
                return $spec->value() === 'foo';
            }))
            ->willReturn(new Set(Citation::class));
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(Citation $citation) use ($command): bool {
                return $citation->identity() === $command->identity() &&
                    $citation->text() === $command->text() &&
                    $citation->recordedEvents()->size() === 1 &&
                    $citation->recordedEvents()->current() instanceof CitationRegistered;
            }));

        $this->assertNull($handler($command));
    }

    /**
     * @expectedException Domain\Exception\CitationAlreadyExistException
     */
    public function testThrowWhenCitationAlreadyExist()
    {
        $handler = new RegisterCitationHandler(
            $repository = $this->createMock(CitationRepositoryInterface::class)
        );
        $command = new RegisterCitation(
            $this->createMock(IdentityInterface::class),
            new Model('foo')
        );
        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(Text $spec): bool {
                return $spec->value() === 'foo';
            }))
            ->willReturn(
                $set = $this->createMock(SetInterface::class)
            );
        $set
            ->expects($this->once())
            ->method('size')
            ->willReturn(2);
        $set
            ->expects($this->once())
            ->method('current')
            ->willReturn(
                new Citation(
                    $this->createMock(IdentityInterface::class),
                    new Model('foo')
                )
            );
        $repository
            ->expects($this->never())
            ->method('add');

        $handler($command);
    }
}