<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type\HttpResource;

use AppBundle\Neo4j\Type\HttpResource\PathType;
use Innmind\Url\Path;
use Innmind\Neo4j\ONM\TypeInterface;
use Innmind\Immutable\{
    SetInterface,
    CollectionInterface,
    Collection
};

class PathTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            TypeInterface::class,
            new PathType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            PathType::identifiers()
        );
        $this->assertSame('string', (string) PathType::identifiers()->type());
        $this->assertSame(PathType::identifiers(), PathType::identifiers());
        $this->assertSame(
            ['http_resource_path'],
            PathType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            PathType::class,
            PathType::fromConfig(
                $this->createMock(CollectionInterface::class)
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new PathType)->forDatabase(new Path('foo'))
        );
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            Path::class,
            (new PathType)->fromDatabase('foo')
        );
        $this->assertSame(
            'foo',
            (string) (new PathType)->fromDatabase('foo')
        );
    }

    public function testIsNullable()
    {
        $this->assertFalse((new PathType)->isNullable());
    }
}
