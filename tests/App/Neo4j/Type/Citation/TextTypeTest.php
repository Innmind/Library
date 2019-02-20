<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type\Citation;

use App\Neo4j\Type\Citation\TextType;
use Domain\Entity\Citation\Text;
use Innmind\Neo4j\ONM\Type;
use PHPUnit\Framework\TestCase;

class TextTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new TextType
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new TextType)->forDatabase(new Text('foo'))
        );
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            Text::class,
            (new TextType)->fromDatabase('foo')
        );
        $this->assertSame(
            'foo',
            (string) (new TextType)->fromDatabase('foo')
        );
    }

    public function testIsNullable()
    {
        $this->assertFalse((new TextType)->isNullable());
    }
}
