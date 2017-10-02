<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type\Image;

use AppBundle\Neo4j\Type\Image\WeightType;
use Domain\Entity\Image\Weight;
use Innmind\Neo4j\ONM\{
    Type,
    Types
};
use Innmind\Immutable\{
    SetInterface,
    MapInterface,
    Map
};
use PHPUnit\Framework\TestCase;

class WeightTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new WeightType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            WeightType::identifiers()
        );
        $this->assertSame('string', (string) WeightType::identifiers()->type());
        $this->assertSame(WeightType::identifiers(), WeightType::identifiers());
        $this->assertSame(
            ['image_weight'],
            WeightType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            WeightType::class,
            WeightType::fromConfig(
                $this->createMock(MapInterface::class),
                new Types
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            42,
            (new WeightType)->forDatabase(new Weight(42))
        );
        $this->assertNull(
            WeightType::fromConfig(
                (new Map('string', 'mixed'))
                    ->put('nullable', null),
                new Types
            )->forDatabase(null)
        );
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            Weight::class,
            (new WeightType)->fromDatabase(42)
        );
        $this->assertSame(
            42,
            (new WeightType)->fromDatabase(42)->toInt()
        );
    }

    public function testIsNullable()
    {
        $this->assertTrue((new WeightType)->isNullable());
    }
}
