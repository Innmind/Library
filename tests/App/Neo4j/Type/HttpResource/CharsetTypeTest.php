<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type\HttpResource;

use App\Neo4j\Type\HttpResource\CharsetType;
use Domain\Entity\HttpResource\Charset;
use Innmind\Neo4j\ONM\Type;
use PHPUnit\Framework\TestCase;

class CharsetTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new CharsetType
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new CharsetType)->forDatabase(new Charset('foo'))
        );
        $this->assertNull(
            (new CharsetType)->forDatabase(null)
        );
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            Charset::class,
            (new CharsetType)->fromDatabase('foo')
        );
        $this->assertSame(
            'foo',
            (string) (new CharsetType)->fromDatabase('foo')
        );
    }

    public function testIsNullable()
    {
        $this->assertTrue((new CharsetType)->isNullable());
    }
}
