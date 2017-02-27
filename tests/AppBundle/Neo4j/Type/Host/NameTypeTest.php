<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type\Host;

use AppBundle\Neo4j\Type\Host\NameType;
use Domain\Entity\Host\Name;
use Innmind\Neo4j\ONM\{
    TypeInterface,
    Types
};
use Innmind\Immutable\{
    SetInterface,
    MapInterface
};

class NameTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            TypeInterface::class,
            new NameType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            NameType::identifiers()
        );
        $this->assertSame('string', (string) NameType::identifiers()->type());
        $this->assertSame(NameType::identifiers(), NameType::identifiers());
        $this->assertSame(
            ['host_name'],
            NameType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            NameType::class,
            NameType::fromConfig(
                $this->createMock(MapInterface::class),
                new Types
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new NameType)->forDatabase(new Name('foo'))
        );
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            Name::class,
            (new NameType)->fromDatabase('foo')
        );
        $this->assertSame(
            'foo',
            (string) (new NameType)->fromDatabase('foo')
        );
    }

    public function testIsNullable()
    {
        $this->assertFalse((new NameType)->isNullable());
    }
}
