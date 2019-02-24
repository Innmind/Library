<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type\HtmlPage;

use App\Neo4j\Type\HtmlPage\AnchorType;
use Domain\Entity\HtmlPage\Anchor;
use Innmind\Neo4j\ONM\Type;
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
