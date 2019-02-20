<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type\Image;

use App\Neo4j\Type\Image\DimensionType;
use Domain\Entity\Image\Dimension;
use Innmind\Neo4j\ONM\Type;
use PHPUnit\Framework\TestCase;

class DimensionTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new DimensionType
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            [24, 42],
            (new DimensionType)->forDatabase(new Dimension(24, 42))
        );
        $this->assertNull(
            (new DimensionType)->forDatabase(null)
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
