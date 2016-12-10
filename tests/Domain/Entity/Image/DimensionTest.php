<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Image;

use Domain\Entity\Image\Dimension;

class DimensionTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $dimension = new Dimension(24, 42);

        $this->assertSame(42, $dimension->height());
        $this->assertSame(24, $dimension->width());
        $this->assertSame('24x42', (string) $dimension);
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenNegativeHeight()
    {
        new Dimension(-1, 24);
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenNegativeWidth()
    {
        new Dimension(42, -1);
    }
}
