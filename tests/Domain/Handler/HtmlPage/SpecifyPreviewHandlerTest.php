<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyPreviewHandler,
    Command\HtmlPage\SpecifyPreview,
    Repository\HtmlPageRepositoryInterface,
    Entity\HtmlPage,
    Entity\HtmlPage\IdentityInterface,
    Event\HtmlPage\PreviewSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Url\UrlInterface;

class SpecifyPreviewHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyPreviewHandler(
            $repository = $this->createMock(HtmlPageRepositoryInterface::class)
        );
        $command = new SpecifyPreview(
            $this->createMock(IdentityInterface::class),
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
