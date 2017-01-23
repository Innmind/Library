<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type;

use AppBundle\Neo4j\Type\ColourType;
use Innmind\Colour\RGBA;
use Innmind\Neo4j\ONM\TypeInterface;
use Innmind\Immutable\{
    SetInterface,
    CollectionInterface,
    Collection
};

class ColourTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            TypeInterface::class,
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
                $this->createMock(CollectionInterface::class)
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
                new Collection(['nullable' => null])
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
                new Collection(['nullable' => null])
            )->isNullable()
        );
    }
}
