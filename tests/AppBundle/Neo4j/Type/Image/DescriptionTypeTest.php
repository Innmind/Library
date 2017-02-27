<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type\Image;

use AppBundle\Neo4j\Type\Image\DescriptionType;
use Domain\Entity\Image\Description;
use Innmind\Neo4j\ONM\{
    TypeInterface,
    Types
};
use Innmind\Immutable\{
    SetInterface,
    MapInterface
};

class DescriptionTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            TypeInterface::class,
            new DescriptionType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            DescriptionType::identifiers()
        );
        $this->assertSame('string', (string) DescriptionType::identifiers()->type());
        $this->assertSame(DescriptionType::identifiers(), DescriptionType::identifiers());
        $this->assertSame(
            ['image_description'],
            DescriptionType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            DescriptionType::class,
            DescriptionType::fromConfig(
                $this->createMock(MapInterface::class),
                new Types
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new DescriptionType)->forDatabase(
                new Description('foo')
            )
        );
    }

    public function testFromDatabase()
    {
        $description = (new DescriptionType)->fromDatabase('foo');
        $this->assertInstanceOf(Description::class, $description);
        $this->assertSame('foo', (string) $description);
    }

    public function testIsNullable()
    {
        $this->assertFalse((new DescriptionType)->isNullable());
    }
}
