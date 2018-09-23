<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Host;

use Domain\Entity\Host\Name;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testInterface()
    {
        $this->assertSame('foo', (string) new Name('foo'));
    }

    /**
     * @expectedException Domain\Exception\DomainException
     */
    public function testThrowWhenEmptyName()
    {
        new Name('');
    }
}
