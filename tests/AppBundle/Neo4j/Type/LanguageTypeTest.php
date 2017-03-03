<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type;

use AppBundle\Neo4j\Type\LanguageType;
use Domain\Model\Language;
use Innmind\Neo4j\ONM\{
    TypeInterface,
    Types
};
use Innmind\Immutable\{
    SetInterface,
    MapInterface,
    Map
};
use PHPUnit\Framework\TestCase;

class LanguageTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            TypeInterface::class,
            new LanguageType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            LanguageType::identifiers()
        );
        $this->assertSame('string', (string) LanguageType::identifiers()->type());
        $this->assertSame(LanguageType::identifiers(), LanguageType::identifiers());
        $this->assertSame(
            ['language'],
            LanguageType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            LanguageType::class,
            LanguageType::fromConfig(
                $this->createMock(MapInterface::class),
                new Types
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new LanguageType)->forDatabase(new Language('foo'))
        );
        $this->assertNull(
            LanguageType::fromConfig(
                (new Map('string', 'mixed'))
                    ->put('nullable', null),
                new Types
            )
                ->forDatabase(null)
        );
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
            LanguageType::fromConfig(
                (new Map('string', 'mixed'))
                    ->put('nullable', null),
                new Types
            )->isNullable()
        );
    }
}
