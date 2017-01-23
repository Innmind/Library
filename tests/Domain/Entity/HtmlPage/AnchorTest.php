<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\HtmlPage;

use Domain\Entity\HtmlPage\Anchor;

class AnchorTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $anchor = new Anchor('#foo');

        $this->assertSame('foo', $anchor->value());
        $this->assertSame('#foo', (string) $anchor);
    }

    public function testEquals()
    {
        $this->assertTrue(
            (new Anchor('#foo'))->equals(new Anchor('foo'))
        );
        $this->assertFalse(
            (new Anchor('foo'))->equals(new Anchor('bar'))
        );
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyAnchor()
    {
        new Anchor('');
    }
}
