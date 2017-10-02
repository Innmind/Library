<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type\HttpResource;

use AppBundle\Neo4j\Type\HttpResource\CharsetType;
use Domain\Entity\HttpResource\Charset;
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

class CharsetTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new CharsetType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            CharsetType::identifiers()
        );
        $this->assertSame('string', (string) CharsetType::identifiers()->type());
        $this->assertSame(CharsetType::identifiers(), CharsetType::identifiers());
        $this->assertSame(
            ['http_resource_charset'],
            CharsetType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            CharsetType::class,
            CharsetType::fromConfig(
                $this->createMock(MapInterface::class),
                new Types
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new CharsetType)->forDatabase(new Charset('foo'))
        );
        $this->assertNull(
            CharsetType::fromConfig(
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
            Charset::class,
            (new CharsetType)->fromDatabase('foo')
        );
        $this->assertSame(
            'foo',
            (string) (new CharsetType)->fromDatabase('foo')
        );
    }

    public function testIsNullable()
    {
        $this->assertTrue((new CharsetType)->isNullable());
    }
}
