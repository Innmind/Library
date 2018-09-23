<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type\HtmlPage;

use App\Neo4j\Type\HtmlPage\AnchorType;
use Domain\Entity\HtmlPage\Anchor;
use Innmind\Neo4j\ONM\{
    Type,
    Types
};
use Innmind\Immutable\{
    SetInterface,
    MapInterface
};
use PHPUnit\Framework\TestCase;

class AnchorTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new AnchorType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            AnchorType::identifiers()
        );
        $this->assertSame('string', (string) AnchorType::identifiers()->type());
        $this->assertSame(AnchorType::identifiers(), AnchorType::identifiers());
        $this->assertSame(
            ['html_page_anchor'],
            AnchorType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            AnchorType::class,
            AnchorType::fromConfig(
                $this->createMock(MapInterface::class),
                new Types
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new AnchorType)->forDatabase(
                new Anchor('foo')
            )
        );
    }

    public function testFromDatabase()
    {
        $anchor = (new AnchorType)->fromDatabase('foo');
        $this->assertInstanceOf(Anchor::class, $anchor);
        $this->assertSame('foo', $anchor->value());
    }

    public function testIsNullable()
    {
        $this->assertFalse((new AnchorType)->isNullable());
    }
}
