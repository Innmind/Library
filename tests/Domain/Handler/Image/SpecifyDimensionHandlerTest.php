<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\Image;

use Domain\{
    Handler\Image\SpecifyDimensionHandler,
    Command\Image\SpecifyDimension,
    Repository\ImageRepositoryInterface,
    Entity\Image,
    Entity\Image\IdentityInterface,
    Entity\Image\Dimension,
    Event\Image\DimensionSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use PHPUnit\Framework\TestCase;

class SpecifyDimensionHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyDimensionHandler(
            $repository = $this->createMock(ImageRepositoryInterface::class)
        );
        $command = new SpecifyDimension(
            $this->createMock(IdentityInterface::class),
            new Dimension(12, 21)
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
        $this->assertCount(1, $image->recordedEvents());
        $this->assertInstanceOf(
            DimensionSpecified::class,
            $image->recordedEvents()->current()
        );
        $this->assertSame($command->dimension(), $image->dimension());
    }
}
