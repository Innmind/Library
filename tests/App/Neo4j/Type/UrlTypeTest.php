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
            (new UrlType)->forDatabase(Url::fromString('foo.com'))
        );
        $this->assertNull(
            UrlType::nullable()->forDatabase(null)
        );
    }

    /**
     * @expectedException LogicException
     */
    public function testThrowWhenNullValueOnNonNullableType()
    {
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
            (string) (new UrlType)->fromDatabase('foo')
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
