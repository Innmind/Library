<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\Image;

use Domain\{
    Handler\Image\SpecifyDimensionHandler,
    Command\Image\SpecifyDimension,
    Repository\ImageRepository,
    Entity\Image,
    Entity\Image\Identity,
    Entity\Image\Dimension,
    Event\Image\DimensionSpecified
};
use Innmind\Url\{
    Path,
    Query
};
use PHPUnit\Framework\TestCase;

class SpecifyDimensionHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyDimensionHandler(
            $repository = $this->createMock(ImageRepository::class)
        );
        $command = new SpecifyDimension(
            $this->createMock(Identity::class),
            new Dimension(12, 21)
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($command->identity())
            ->willReturn(
                $image = new Image(
                    $command->identity(),
                    Path::none(),
                    Query::none()
                )
            );

        $this->assertNull($handler($command));
        $this->assertCount(1, $image->recordedEvents());
        $this->assertInstanceOf(
            DimensionSpecified::class,
            $image->recordedEvents()->first()
        );
        $this->assertSame($command->dimension(), $image->dimension());
    }
}
