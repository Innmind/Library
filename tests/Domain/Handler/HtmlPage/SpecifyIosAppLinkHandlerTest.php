<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyIosAppLinkHandler,
    Command\HtmlPage\SpecifyIosAppLink,
    Repository\HtmlPageRepositoryInterface,
    Entity\HtmlPage,
    Entity\HtmlPage\IdentityInterface,
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
            $repository = $this->createMock(HtmlPageRepositoryInterface::class)
        );
        $command = new SpecifyIosAppLink(
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
        $this->assertSame($command->url(), $page->iosAppLink());
        $this->assertInstanceOf(
            IosAppLinkSpecified::class,
            $page->recordedEvents()->current()
        );
    }
}
