<?php
declare(strict_types = 1);

namespace Tests\Web\Neo4j\Type\Citation;

use Web\Neo4j\Type\Citation\TextType;
use Domain\Entity\Citation\Text;
use Innmind\Neo4j\ONM\{
    Type,
    Types
};
use Innmind\Immutable\{
    SetInterface,
    MapInterface
};
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

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            TextType::identifiers()
        );
        $this->assertSame('string', (string) TextType::identifiers()->type());
        $this->assertSame(TextType::identifiers(), TextType::identifiers());
        $this->assertSame(
            ['citation_text'],
            TextType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            TextType::class,
            TextType::fromConfig(
                $this->createMock(MapInterface::class),
                new Types
            )
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
