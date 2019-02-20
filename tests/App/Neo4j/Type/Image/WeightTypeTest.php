<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type\Image;

use App\Neo4j\Type\Image\WeightType;
use Domain\Entity\Image\Weight;
use Innmind\Neo4j\ONM\Type;
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

    public function testForDatabase()
    {
        $this->assertSame(
            42,
            (new WeightType)->forDatabase(new Weight(42))
        );
        $this->assertNull(
            (new WeightType)->forDatabase(null)
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
