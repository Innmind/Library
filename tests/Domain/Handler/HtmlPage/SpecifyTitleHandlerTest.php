<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyTitleHandler,
    Command\HtmlPage\SpecifyTitle,
    Repository\HtmlPageRepositoryInterface,
    Entity\HtmlPage,
    Entity\HtmlPage\IdentityInterface,
    Event\HtmlPage\TitleSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use PHPUnit\Framework\TestCase;

class SpecifyTitleHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyTitleHandler(
            $repository = $this->createMock(HtmlPageRepositoryInterface::class)
        );
        $command = new SpecifyTitle(
            $this->createMock(IdentityInterface::class),
            'foo'
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
        $this->assertSame('foo', $page->title());
        $this->assertInstanceOf(
            TitleSpecified::class,
            $page->recordedEvents()->current()
        );
    }
}
