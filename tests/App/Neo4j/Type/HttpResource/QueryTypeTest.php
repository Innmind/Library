<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type\HttpResource;

use App\Neo4j\Type\HttpResource\QueryType;
use Innmind\Url\{
    Query,
    NullQuery
};
use Innmind\Neo4j\ONM\{
    Type,
    Types
};
use Innmind\Immutable\{
    SetInterface,
    MapInterface
};
use PHPUnit\Framework\TestCase;

class QueryTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
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
                $this->createMock(MapInterface::class),
                new Types
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
        $this->assertInstanceOf(
            NullQuery::class,
            (new QueryType)->fromDatabase('')
        );
        $this->assertInstanceOf(
            NullQuery::class,
            (new QueryType)->fromDatabase(null)
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
