<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyAnchorsHandler,
    Command\HtmlPage\SpecifyAnchors,
    Repository\HtmlPageRepositoryInterface,
    Entity\HtmlPage,
    Entity\HtmlPage\IdentityInterface,
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
            $repository = $this->createMock(HtmlPageRepositoryInterface::class)
        );
        $command = new SpecifyAnchors(
            $this->createMock(IdentityInterface::class),
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
