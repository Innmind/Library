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
    Path,
    Query,
    Url,
};
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
        $this->assertSame($command->url(), $page->iosAppLink());
        $this->assertInstanceOf(
            IosAppLinkSpecified::class,
            $page->recordedEvents()->first()
        );
    }
}
