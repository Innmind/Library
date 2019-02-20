<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type;

use App\Neo4j\Type\LanguageType;
use Domain\Model\Language;
use Innmind\Neo4j\ONM\Type;
use PHPUnit\Framework\TestCase;

class LanguageTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new LanguageType
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new LanguageType)->forDatabase(new Language('foo'))
        );
        $this->assertNull(
            LanguageType::nullable()->forDatabase(null)
        );
    }

    public function testThrowWhenNullValueOnNonNullableType()
    {
        $this->expectException(\LogicException::class);

        (new LanguageType)->forDatabase(null);
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            Language::class,
            (new LanguageType)->fromDatabase('foo')
        );
        $this->assertSame(
            'foo',
            (string) (new LanguageType)->fromDatabase('foo')
        );
    }

    public function testIsNullable()
    {
        $this->assertFalse((new LanguageType)->isNullable());
        $this->assertTrue(
            LanguageType::nullable()->isNullable()
        );
    }
}
