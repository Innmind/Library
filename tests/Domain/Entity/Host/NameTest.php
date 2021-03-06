<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Host;

use Domain\{
    Entity\Host\Name,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testInterface()
    {
        $this->assertSame('foo', (string) new Name('foo'));
    }

    public function testThrowWhenEmptyName()
    {
        $this->expectException(DomainException::class);

        new Name('');
    }
}
