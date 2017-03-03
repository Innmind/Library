<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Image;

use Domain\Entity\Image\Weight;
use PHPUnit\Framework\TestCase;

class WeightTest extends TestCase
{
    public function testInterface()
    {
        $this->assertSame(42, (new Weight(42))->toInt());
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenValueBelowZero()
    {
        new Weight(-1);
    }
}
