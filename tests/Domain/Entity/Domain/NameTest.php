<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Domain;

use Domain\Entity\Domain\Name;

class NameTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertSame('foo', (string) new Name('foo'));
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyName()
    {
        new Name('');
    }
}
