<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\Image;

use Domain\{
    Handler\Image\AddDescriptionHandler,
    Command\Image\AddDescription,
    Repository\ImageRepositoryInterface,
    Entity\Image,
    Entity\Image\IdentityInterface,
    Entity\Image\Description,
    Event\Image\DescriptionAdded
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use PHPUnit\Framework\TestCase;

class AddDescriptionHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new AddDescriptionHandler(
            $repository = $this->createMock(ImageRepositoryInterface::class)
        );
        $command = new AddDescription(
            $this->createMock(IdentityInterface::class),
            new Description('foobar')
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($command->identity())
            ->willReturn(
                $image = new Image(
                    $command->identity(),
                    $this->createMock(PathInterface::class),
                    $this->createMock(QueryInterface::class)
                )
            );

        $this->assertNull($handler($command));
        $this->assertSame(
            [$command->description()],
            $image->descriptions()->toPrimitive()
        );
        $this->assertInstanceOf(
            DescriptionAdded::class,
            $image->recordedEvents()->current()
        );
    }
}
