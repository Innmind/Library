<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\Image;

use Domain\{
    Handler\Image\SpecifyWeightHandler,
    Command\Image\SpecifyWeight,
    Repository\ImageRepositoryInterface,
    Entity\Image,
    Entity\Image\IdentityInterface,
    Entity\Image\Weight,
    Event\Image\WeightSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use PHPUnit\Framework\TestCase;

class SpecifyWeightHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyWeightHandler(
            $repository = $this->createMock(ImageRepositoryInterface::class)
        );
        $command = new SpecifyWeight(
            $this->createMock(IdentityInterface::class),
            new Weight(12, 21)
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
            WeightSpecified::class,
            $image->recordedEvents()->current()
        );
        $this->assertSame($command->weight(), $image->weight());
    }
}
