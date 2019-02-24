<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type\Image;

use App\Neo4j\Type\Image\DescriptionType;
use Domain\Entity\Image\Description;
use Innmind\Neo4j\ONM\Type;
use PHPUnit\Framework\TestCase;

class DescriptionTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new DescriptionType
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new DescriptionType)->forDatabase(
                new Description('foo')
            )
        );
    }

    public function testFromDatabase()
    {
        $description = (new DescriptionType)->fromDatabase('foo');
        $this->assertInstanceOf(Description::class, $description);
        $this->assertSame('foo', (string) $description);
    }

    public function testIsNullable()
    {
        $this->assertFalse((new DescriptionType)->isNullable());
    }
}
