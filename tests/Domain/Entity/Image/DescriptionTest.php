<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Image;

use Domain\{
    Entity\Image\Description,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
    public function testInterface()
    {
        $this->assertSame('foo', (string) new Description('foo'));
    }

    public function testEquals()
    {
        $this->assertTrue(
            (new Description('foo'))->equals(new Description('foo'))
        );
        $this->assertFalse(
            (new Description('foo'))->equals(new Description('bar'))
        );
    }

    public function testThrowWhenEmptyValue()
    {
        $this->expectException(DomainException::class);

        new Description('');
    }
}
