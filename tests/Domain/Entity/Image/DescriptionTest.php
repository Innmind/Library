<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Image;

use Domain\Entity\Image\Description;

class DescriptionTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyValue()
    {
        new Description('');
    }
}
