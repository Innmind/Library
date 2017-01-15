<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type\HttpResource;

use AppBundle\Neo4j\Type\HttpResource\QueryType;
use Innmind\Url\Query;
use Innmind\Neo4j\ONM\TypeInterface;
use Innmind\Immutable\{
    SetInterface,
    CollectionInterface,
    Collection
};

class QueryTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            TypeInterface::class,
            new QueryType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            QueryType::identifiers()
        );
        $this->assertSame('string', (string) QueryType::identifiers()->type());
        $this->assertSame(QueryType::identifiers(), QueryType::identifiers());
        $this->assertSame(
            ['http_resource_query'],
            QueryType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            QueryType::class,
            QueryType::fromConfig(
                $this->createMock(CollectionInterface::class)
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new QueryType)->forDatabase(new Query('foo'))
        );
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            Query::class,
            (new QueryType)->fromDatabase('foo')
        );
        $this->assertSame(
            'foo',
            (string) (new QueryType)->fromDatabase('foo')
        );
    }

    public function testIsNullable()
    {
        $this->assertFalse((new QueryType)->isNullable());
    }
}
