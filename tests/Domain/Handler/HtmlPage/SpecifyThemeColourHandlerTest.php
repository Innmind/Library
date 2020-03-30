<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyThemeColourHandler,
    Command\HtmlPage\SpecifyThemeColour,
    Repository\HtmlPageRepository,
    Entity\HtmlPage,
    Entity\HtmlPage\Identity,
    Event\HtmlPage\ThemeColourSpecified
};
use Innmind\Url\{
    Path,
    Query
};
use Innmind\Colour\RGBA;
use PHPUnit\Framework\TestCase;

class SpecifyThemeColourHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyThemeColourHandler(
            $repository = $this->createMock(HtmlPageRepository::class)
        );
        $command = new SpecifyThemeColour(
            $this->createMock(Identity::class),
            RGBA::of('39f')
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
        $this->assertSame($command->colour(), $page->themeColour());
        $this->assertInstanceOf(
            ThemeColourSpecified::class,
            $page->recordedEvents()->first()
        );
    }
}
