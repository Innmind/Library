<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type;

use AppBundle\Neo4j\Type\UrlType;
use Innmind\Url\Url;
use Innmind\Neo4j\ONM\TypeInterface;
use Innmind\Immutable\{
    SetInterface,
    CollectionInterface,
    Collection
};

class UrlTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            TypeInterface::class,
            new UrlType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            UrlType::identifiers()
        );
        $this->assertSame('string', (string) UrlType::identifiers()->type());
        $this->assertSame(UrlType::identifiers(), UrlType::identifiers());
        $this->assertSame(
            ['url'],
            UrlType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            UrlType::class,
            UrlType::fromConfig(
                $this->createMock(CollectionInterface::class)
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo.com',
            (new UrlType)->forDatabase(Url::fromString('foo.com'))
        );
        $this->assertNull(
            UrlType::fromConfig(
                new Collection(['nullable' => null])
            )
                ->forDatabase(null)
        );
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            Url::class,
            (new UrlType)->fromDatabase('foo')
        );
        $this->assertSame(
            'foo',
            (string) (new UrlType)->fromDatabase('foo')
        );
    }

    public function testIsNullable()
    {
        $this->assertFalse((new UrlType)->isNullable());
        $this->assertTrue(
            UrlType::fromConfig(
                new Collection(['nullable' => null])
            )->isNullable()
        );
    }
}
