<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type\Image;

use AppBundle\Neo4j\Type\Image\DimensionType;
use Domain\Entity\Image\Dimension;
use Innmind\Neo4j\ONM\TypeInterface;
use Innmind\Immutable\{
    SetInterface,
    CollectionInterface,
    Collection
};

class DimensionTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            TypeInterface::class,
            new DimensionType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            DimensionType::identifiers()
        );
        $this->assertSame('string', (string) DimensionType::identifiers()->type());
        $this->assertSame(DimensionType::identifiers(), DimensionType::identifiers());
        $this->assertSame(
            ['image_dimension'],
            DimensionType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            DimensionType::class,
            DimensionType::fromConfig(
                $this->createMock(CollectionInterface::class)
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            [24, 42],
            (new DimensionType)->forDatabase(new Dimension(24, 42))
        );
        $this->assertNull(
            DimensionType::fromConfig(
                new Collection(['nullable' => null])
            )->forDatabase(null)
        );
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            Dimension::class,
            (new DimensionType)->fromDatabase([24, 42])
        );
        $this->assertSame(
            '24x42',
            (string) (new DimensionType)->fromDatabase([24, 42])
        );
    }

    public function testIsNullable()
    {
        $this->assertTrue((new DimensionType)->isNullable());
    }
}