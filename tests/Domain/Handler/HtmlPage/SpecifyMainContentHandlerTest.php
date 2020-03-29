<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyMainContentHandler,
    Command\HtmlPage\SpecifyMainContent,
    Repository\HtmlPageRepository,
    Entity\HtmlPage,
    Entity\HtmlPage\Identity,
    Event\HtmlPage\MainContentSpecified
};
use Innmind\Url\{
    Path,
    Query
};
use PHPUnit\Framework\TestCase;

class SpecifyMainContentHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyMainContentHandler(
            $repository = $this->createMock(HtmlPageRepository::class)
        );
        $command = new SpecifyMainContent(
            $this->createMock(Identity::class),
            'foo'
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($command->identity())
            ->willReturn(
                $page = new HtmlPage(
                    $command->identity(),
                    Path::none(),
                    Query::none()
                )
            );

        $this->assertNull($handler($command));
        $this->assertSame('foo', $page->mainContent());
        $this->assertInstanceOf(
            MainContentSpecified::class,
            $page->recordedEvents()->first()
        );
    }
}
