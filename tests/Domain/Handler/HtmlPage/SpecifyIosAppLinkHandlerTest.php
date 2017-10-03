<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyIosAppLinkHandler,
    Command\HtmlPage\SpecifyIosAppLink,
    Repository\HtmlPageRepository,
    Entity\HtmlPage,
    Entity\HtmlPage\Identity,
    Event\HtmlPage\IosAppLinkSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class SpecifyIosAppLinkHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyIosAppLinkHandler(
            $repository = $this->createMock(HtmlPageRepository::class)
        );
        $command = new SpecifyIosAppLink(
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
        $this->assertSame($command->url(), $page->iosAppLink());
        $this->assertInstanceOf(
            IosAppLinkSpecified::class,
            $page->recordedEvents()->current()
        );
    }
}
