<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type\HttpResource;

use App\Neo4j\Type\HttpResource\QueryType;
use Innmind\Url\Query;
use Innmind\Neo4j\ONM\Type;
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

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new QueryType)->forDatabase(Query::of('foo'))
        );
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            Query::class,
            (new QueryType)->fromDatabase('foo')
        );
        $this->assertEquals(
            Query::none(),
            (new QueryType)->fromDatabase('')
        );
        $this->assertEquals(
            Query::none(),
            (new QueryType)->fromDatabase(null)
        );
        $this->assertSame(
            'foo',
            (new QueryType)->fromDatabase('foo')->toString()
        );
    }

    public function testIsNullable()
    {
        $this->assertFalse((new QueryType)->isNullable());
    }
}
