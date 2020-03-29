<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyTitleHandler,
    Command\HtmlPage\SpecifyTitle,
    Repository\HtmlPageRepository,
    Entity\HtmlPage,
    Entity\HtmlPage\Identity,
    Event\HtmlPage\TitleSpecified
};
use Innmind\Url\{
    Path,
    Query
};
use PHPUnit\Framework\TestCase;

class SpecifyTitleHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyTitleHandler(
            $repository = $this->createMock(HtmlPageRepository::class)
        );
        $command = new SpecifyTitle(
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
        $this->assertSame('foo', $page->title());
        $this->assertInstanceOf(
            TitleSpecified::class,
            $page->recordedEvents()->first()
        );
    }
}
