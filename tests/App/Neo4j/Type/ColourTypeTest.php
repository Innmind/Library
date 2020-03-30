<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type;

use App\Neo4j\Type\ColourType;
use Innmind\Colour\RGBA;
use Innmind\Neo4j\ONM\Type;
use PHPUnit\Framework\TestCase;

class ColourTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new ColourType
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            '#3399ff',
            (new ColourType)->forDatabase(RGBA::of('39F'))
        );
        $this->assertNull(
            ColourType::nullable()->forDatabase(null)
        );
    }

    public function testThrowWhenNullValueOnNonNullableType()
    {
        $this->expectException(\Error::class);

        (new ColourType)->forDatabase(null);
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            RGBA::class,
            (new ColourType)->fromDatabase('39F')
        );
        $this->assertSame(
            '#3399ff',
            (new ColourType)->fromDatabase('#39F')->toString()
        );
    }

    public function testIsNullable()
    {
        $this->assertFalse((new ColourType)->isNullable());
        $this->assertTrue(
            ColourType::nullable()->isNullable()
        );
    }
}
