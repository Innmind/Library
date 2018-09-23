<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\FlagAsJournalHandler,
    Command\HtmlPage\FlagAsJournal,
    Repository\HtmlPageRepository,
    Entity\HtmlPage,
    Entity\HtmlPage\Identity,
    Event\HtmlPage\FlaggedAsJournal
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use PHPUnit\Framework\TestCase;

class FlagAsJournalHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new FlagAsJournalHandler(
            $repository = $this->createMock(HtmlPageRepository::class)
        );
        $command = new FlagAsJournal(
            $this->createMock(Identity::class)
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($command->identity())
            ->willReturn(
                $page = new HtmlPage(
                    $command->identity(),
                    $this->createMock(PathInterface::class),
                    $this->createMock(QueryInterface::class)
                )
            );

        $this->assertNull($handler($command));
        $this->assertTrue($page->isJournal());
        $this->assertInstanceOf(
            FlaggedAsJournal::class,
            $page->recordedEvents()->current()
        );
    }
}
