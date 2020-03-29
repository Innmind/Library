<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type;

use App\Neo4j\Type\UrlType;
use Innmind\Url\Url;
use Innmind\Neo4j\ONM\Type;
use PHPUnit\Framework\TestCase;

class UrlTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new UrlType
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo.com',
            (new UrlType)->forDatabase(Url::of('foo.com'))
        );
        $this->assertNull(
            UrlType::nullable()->forDatabase(null)
        );
    }

    public function testThrowWhenNullValueOnNonNullableType()
    {
        $this->expectException(\LogicException::class);

        (new UrlType)->forDatabase(null);
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            Url::class,
            (new UrlType)->fromDatabase('foo')
        );
        $this->assertSame(
            'foo',
            (new UrlType)->fromDatabase('foo')->toString()
        );
    }

    public function testIsNullable()
    {
        $this->assertFalse((new UrlType)->isNullable());
        $this->assertTrue(
            UrlType::nullable()->isNullable()
        );
    }
}
