<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type\Citation;

use AppBundle\Neo4j\Type\Citation\TextType;
use Domain\Entity\Citation\Text;
use Innmind\Neo4j\ONM\TypeInterface;
use Innmind\Immutable\{
    SetInterface,
    CollectionInterface
};

class TextTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            TypeInterface::class,
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
                $this->createMock(CollectionInterface::class)
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
}


