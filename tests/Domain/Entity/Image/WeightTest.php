<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Image;

use Domain\{
    Entity\Image\Weight,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;

class WeightTest extends TestCase
{
    public function testInterface()
    {
        $this->assertSame(42, (new Weight(42))->toInt());
        $this->assertSame(0, (new Weight(0))->toInt());
    }

    public function testThrowWhenValueBelowZero()
    {
        $this->expectException(DomainException::class);

        new Weight(-1);
    }
}
