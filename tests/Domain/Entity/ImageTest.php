<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Image,
    Entity\HttpResource,
    Entity\Image\Identity,
    Entity\Image\Description,
    Entity\Image\Weight,
    Entity\Image\Dimension,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\ImageRegistered,
    Event\Image\DimensionSpecified,
    Event\Image\WeightSpecified,
    Event\Image\DescriptionAdded
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testInterface()
    {
        $image = new Image(
            $identity = $this->createMock(Identity::class),
            $path = $this->createMock(PathInterface::class),
            $query = $this->createMock(QueryInterface::class)
        );

        $this->assertInstanceOf(HttpResource::class, $image);
        $this->assertSame($identity, $image->identity());
        $this->assertSame($path, $image->path());
        $this->assertSame($query, $image->query());
        $this->assertFalse($image->isDimensionKnown());
        $this->assertFalse($image->isWeightKnown());
        $this->assertCount(0, $image->descriptions());
        $this->assertCount(0, $image->recordedEvents());
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidIdentity()
    {
        new Image(
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
    }

    public function testRegister()
    {
        $image = Image::register(
            $identity = $this->createMock(Identity::class),
            $path = $this->createMock(PathInterface::class),
            $query = $this->createMock(QueryInterface::class)
        );

        $this->assertInstanceOf(Image::class, $image);
        $this->assertCount(1, $image->recordedEvents());
        $this->assertInstanceOf(
            ImageRegistered::class,
            $image->recordedEvents()->current()
        );
        $this->assertSame(
            $identity,
            $image->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $path,
            $image->recordedEvents()->current()->path()
        );
        $this->assertSame(
            $query,
            $image->recordedEvents()->current()->query()
        );
    }

    public function testSpecifyDimension()
    {
        $image = new Image(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertSame(
            $image,
            $image->specifyDimension(
                $dimension = new Dimension(24, 42)
            )
        );
        $this->assertTrue($image->isDimensionKnown());
        $this->assertSame($dimension, $image->dimension());
        $this->assertCount(1, $image->recordedEvents());
        $this->assertInstanceOf(
            DimensionSpecified::class,
            $image->recordedEvents()->current()
        );
        $this->assertSame(
            $image->identity(),
            $image->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $image->dimension(),
            $image->recordedEvents()->current()->dimension()
        );
    }

    public function testSpecifyWeight()
    {
        $image = new Image(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertSame(
            $image,
            $image->specifyWeight($weight = new Weight(42))
        );
        $this->assertTrue($image->isWeightKnown());
        $this->assertSame($weight, $image->weight());
        $this->assertCount(1, $image->recordedEvents());
        $this->assertInstanceOf(
            WeightSpecified::class,
            $image->recordedEvents()->current()
        );
        $this->assertSame(
            $image->identity(),
            $image->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $weight,
            $image->recordedEvents()->current()->weight()
        );
    }

    public function testAddDescription()
    {
        $image = new Image(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertSame(
            $image,
            $image->addDescription($description = new Description('foobar'))
        );
        $this->assertSame([$description], $image->descriptions()->toPrimitive());
        $this->assertSame(
            Description::class,
            (string) $image->descriptions()->type()
        );
        $this->assertCount(1, $image->recordedEvents());
        $this->assertInstanceOf(
            DescriptionAdded::class,
            $image->recordedEvents()->current()
        );
        $this->assertSame(
            $image->identity(),
            $image->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $description,
            $image->recordedEvents()->current()->description()
        );
    }

    public function testDoesntRecordEventWhenDescriptionAlreadyInSet()
    {
        $image = new Image(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $image->addDescription(new Description('foobar'));
        $events = $image->recordedEvents();
        $this->assertSame(
            $image,
            $image->addDescription(new Description('foobar'))
        );
        $this->assertSame($events, $image->recordedEvents());
    }
}
