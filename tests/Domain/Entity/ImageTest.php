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
    Event\Image\DescriptionAdded,
    Exception\InvalidArgumentException,
};
use Innmind\Url\{
    Path,
    Query,
};
use function Innmind\Immutable\unwrap;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testInterface()
    {
        $image = new Image(
            $identity = $this->createMock(Identity::class),
            $path = Path::none(),
            $query = Query::none()
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

    public function testThrowWhenInvalidIdentity()
    {
        $this->expectException(InvalidArgumentException::class);

        new Image(
            $this->createMock(ResourceIdentity::class),
            Path::none(),
            Query::none()
        );
    }

    public function testRegister()
    {
        $image = Image::register(
            $identity = $this->createMock(Identity::class),
            $path = Path::none(),
            $query = Query::none()
        );

        $this->assertInstanceOf(Image::class, $image);
        $this->assertCount(1, $image->recordedEvents());
        $this->assertInstanceOf(
            ImageRegistered::class,
            $image->recordedEvents()->first()
        );
        $this->assertSame(
            $identity,
            $image->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $path,
            $image->recordedEvents()->first()->path()
        );
        $this->assertSame(
            $query,
            $image->recordedEvents()->first()->query()
        );
    }

    public function testSpecifyDimension()
    {
        $image = new Image(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
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
            $image->recordedEvents()->first()
        );
        $this->assertSame(
            $image->identity(),
            $image->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $image->dimension(),
            $image->recordedEvents()->first()->dimension()
        );
    }

    public function testSpecifyWeight()
    {
        $image = new Image(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
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
            $image->recordedEvents()->first()
        );
        $this->assertSame(
            $image->identity(),
            $image->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $weight,
            $image->recordedEvents()->first()->weight()
        );
    }

    public function testAddDescription()
    {
        $image = new Image(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
        );

        $this->assertSame(
            $image,
            $image->addDescription($description = new Description('foobar'))
        );
        $this->assertSame([$description], unwrap($image->descriptions()));
        $this->assertSame(
            Description::class,
            (string) $image->descriptions()->type()
        );
        $this->assertCount(1, $image->recordedEvents());
        $this->assertInstanceOf(
            DescriptionAdded::class,
            $image->recordedEvents()->first()
        );
        $this->assertSame(
            $image->identity(),
            $image->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $description,
            $image->recordedEvents()->first()->description()
        );
    }

    public function testDoesntRecordEventWhenDescriptionAlreadyInSet()
    {
        $image = new Image(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
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
