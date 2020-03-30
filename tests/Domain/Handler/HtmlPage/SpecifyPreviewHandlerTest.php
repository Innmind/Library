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
    Path,
    Query,
    Url,
};
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
            Url::of('http://example.com')
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
        $this->assertSame($command->url(), $page->preview());
        $this->assertInstanceOf(
            PreviewSpecified::class,
            $page->recordedEvents()->first()
        );
    }
}
