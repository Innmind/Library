<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type;

use AppBundle\Neo4j\Type\ColourType;
use Innmind\Colour\RGBA;
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

class ColourTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new ColourType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            ColourType::identifiers()
        );
        $this->assertSame('string', (string) ColourType::identifiers()->type());
        $this->assertSame(ColourType::identifiers(), ColourType::identifiers());
        $this->assertSame(
            ['colour'],
            ColourType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            ColourType::class,
            ColourType::fromConfig(
                $this->createMock(MapInterface::class),
                new Types
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            '#3399ff',
            (new ColourType)->forDatabase(RGBA::fromString('39F'))
        );
        $this->assertNull(
            ColourType::fromConfig(
                (new Map('string', 'mixed'))
                    ->put('nullable', null),
                new Types
            )
                ->forDatabase(null)
        );
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            RGBA::class,
            (new ColourType)->fromDatabase('39F')
        );
        $this->assertSame(
            '#3399ff',
            (string) (new ColourType)->fromDatabase('#39F')
        );
    }

    public function testIsNullable()
    {
        $this->assertFalse((new ColourType)->isNullable());
        $this->assertTrue(
            ColourType::fromConfig(
                (new Map('string', 'mixed'))
                    ->put('nullable', null),
                new Types
            )->isNullable()
        );
    }
}
