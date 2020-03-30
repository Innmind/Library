<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type\HttpResource;

use App\Neo4j\Type\HttpResource\PathType;
use Innmind\Url\Path;
use Innmind\Neo4j\ONM\Type;
use PHPUnit\Framework\TestCase;

class PathTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new PathType
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new PathType)->forDatabase(Path::of('foo'))
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
            (new PathType)->fromDatabase('foo')->toString()
        );
    }

    public function testIsNullable()
    {
        $this->assertFalse((new PathType)->isNullable());
    }
}
