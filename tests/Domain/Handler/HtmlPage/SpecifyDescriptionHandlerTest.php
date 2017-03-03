<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HtmlPage;

use Domain\{
    Handler\HtmlPage\SpecifyDescriptionHandler,
    Command\HtmlPage\SpecifyDescription,
    Repository\HtmlPageRepositoryInterface,
    Entity\HtmlPage,
    Entity\HtmlPage\IdentityInterface,
    Event\HtmlPage\DescriptionSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use PHPUnit\Framework\TestCase;

class SpecifyDescriptionHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyDescriptionHandler(
            $repository = $this->createMock(HtmlPageRepositoryInterface::class)
        );
        $command = new SpecifyDescription(
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
        $this->assertSame('foo', $page->description());
        $this->assertInstanceOf(
            DescriptionSpecified::class,
            $page->recordedEvents()->current()
        );
    }
}
