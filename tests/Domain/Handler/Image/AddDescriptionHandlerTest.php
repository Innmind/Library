<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\Image;

use Domain\{
    Handler\Image\AddDescriptionHandler,
    Command\Image\AddDescription,
    Repository\ImageRepositoryInterface,
    Entity\Image,
    Entity\Image\IdentityInterface,
    Event\Image\DescriptionAdded
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

class AddDescriptionHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new AddDescriptionHandler(
            $repository = $this->createMock(ImageRepositoryInterface::class)
        );
        $command = new AddDescription(
            $this->createMock(IdentityInterface::class),
            'foobar'
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
        $this->assertSame(['foobar'], $image->descriptions()->toPrimitive());
        $this->assertInstanceOf(
            DescriptionAdded::class,
            $image->recordedEvents()->current()
        );
    }
}
