<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyAndroidAppLinkHandler,
    Command\HtmlPage\SpecifyAndroidAppLink,
    Repository\HtmlPageRepository,
    Entity\HtmlPage,
    Entity\HtmlPage\Identity,
    Event\HtmlPage\AndroidAppLinkSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class SpecifyAndroidAppLinkHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyAndroidAppLinkHandler(
            $repository = $this->createMock(HtmlPageRepository::class)
        );
        $command = new SpecifyAndroidAppLink(
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
        $this->assertSame($command->url(), $page->androidAppLink());
        $this->assertInstanceOf(
            AndroidAppLinkSpecified::class,
            $page->recordedEvents()->current()
        );
    }
}
