<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Image;

use Domain\{
    Entity\Image\Dimension,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;

class DimensionTest extends TestCase
{
    public function testInterface()
    {
        $dimension = new Dimension(24, 42);

        $this->assertSame(42, $dimension->height());
        $this->assertSame(24, $dimension->width());
        $this->assertSame('24x42', (string) $dimension);
    }

    public function testBoundsAllowed()
    {
        $dimension = new Dimension(0, 0);

        $this->assertSame(0, $dimension->height());
        $this->assertSame(0, $dimension->width());
        $this->assertSame('0x0', (string) $dimension);
    }

    public function testThrowWhenNegativeHeight()
    {
        $this->expectException(DomainException::class);

        new Dimension(-1, 24);
    }

    public function testThrowWhenNegativeWidth()
    {
        $this->expectException(DomainException::class);

        new Dimension(42, -1);
    }
}
