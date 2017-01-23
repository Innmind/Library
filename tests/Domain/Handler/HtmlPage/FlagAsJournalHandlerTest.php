<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\FlagAsJournalHandler,
    Command\HtmlPage\FlagAsJournal,
    Repository\HtmlPageRepositoryInterface,
    Entity\HtmlPage,
    Entity\HtmlPage\IdentityInterface,
    Event\HtmlPage\FlaggedAsJournal
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

class FlagAsJournalHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new FlagAsJournalHandler(
            $repository = $this->createMock(HtmlPageRepositoryInterface::class)
        );
        $command = new FlagAsJournal(
            $this->createMock(IdentityInterface::class)
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
