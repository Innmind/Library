<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyPreviewHandler,
    Command\HtmlPage\SpecifyPreview,
    Repository\HtmlPageRepository,
    Entity\HtmlPage,
    Entity\HtmlPage\Identity,
    Event\HtmlPage\PreviewSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class SpecifyPreviewHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyPreviewHandler(
            $repository = $this->createMock(HtmlPageRepository::class)
        );
        $command = new SpecifyPreview(
            $this->createMock(Identity::class),
            $this->createMock(UrlInterface::class)
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
        $this->assertSame($command->url(), $page->preview());
        $this->assertInstanceOf(
            PreviewSpecified::class,
            $page->recordedEvents()->current()
        );
    }
}
