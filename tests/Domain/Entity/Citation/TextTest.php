<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Citation;

use Domain\Entity\Citation\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertSame('foo', (string) new Text('foo'));
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyText()
    {
        new Text('');
    }
}
