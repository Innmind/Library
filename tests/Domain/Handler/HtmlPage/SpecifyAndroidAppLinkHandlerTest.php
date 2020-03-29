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
    Path,
    Query,
    Url,
};
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
        $this->assertSame($command->url(), $page->androidAppLink());
        $this->assertInstanceOf(
            AndroidAppLinkSpecified::class,
            $page->recordedEvents()->first()
        );
    }
}
