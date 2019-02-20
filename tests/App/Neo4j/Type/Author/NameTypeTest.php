<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type\Author;

use App\Neo4j\Type\Author\NameType;
use Domain\Entity\Author\Name;
use Innmind\Neo4j\ONM\Type;
use PHPUnit\Framework\TestCase;

class NameTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new NameType
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
