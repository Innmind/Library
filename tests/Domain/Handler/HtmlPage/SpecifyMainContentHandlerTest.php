<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyMainContentHandler,
    Command\HtmlPage\SpecifyMainContent,
    Repository\HtmlPageRepositoryInterface,
    Entity\HtmlPage,
    Entity\HtmlPage\IdentityInterface,
    Event\HtmlPage\MainContentSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

class SpecifyMainContentHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyMainContentHandler(
            $repository = $this->createMock(HtmlPageRepositoryInterface::class)
        );
        $command = new SpecifyMainContent(
            $this->createMock(IdentityInterface::class),
            'foo'
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
        $this->assertSame('foo', $page->mainContent());
        $this->assertInstanceOf(
            MainContentSpecified::class,
            $page->recordedEvents()->current()
        );
    }
}
