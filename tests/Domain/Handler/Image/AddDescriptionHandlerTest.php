<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\Image;

use Domain\{
    Handler\Image\AddDescriptionHandler,
    Command\Image\AddDescription,
    Repository\ImageRepository,
    Entity\Image,
    Entity\Image\Identity,
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
            $repository = $this->createMock(ImageRepository::class)
        );
        $command = new AddDescription(
            $this->createMock(Identity::class),
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
