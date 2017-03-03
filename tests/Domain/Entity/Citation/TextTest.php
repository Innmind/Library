<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Citation;

use Domain\Entity\Citation\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
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
