<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyAnchorsHandler,
    Command\HtmlPage\SpecifyAnchors,
    Repository\HtmlPageRepository,
    Entity\HtmlPage,
    Entity\HtmlPage\Identity,
    Entity\HtmlPage\Anchor,
    Event\HtmlPage\AnchorsSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class SpecifyAnchorsHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyAnchorsHandler(
            $repository = $this->createMock(HtmlPageRepository::class)
        );
        $command = new SpecifyAnchors(
            $this->createMock(Identity::class),
            new Set(Anchor::class)
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
        $this->assertSame($command->anchors(), $page->anchors());
        $this->assertInstanceOf(
            AnchorsSpecified::class,
            $page->recordedEvents()->current()
        );
    }
}
