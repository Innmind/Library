<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Citation;

use Domain\{
    Entity\Citation\Text,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    public function testInterface()
    {
        $this->assertSame('foo', (string) new Text('foo'));
    }

    public function testThrowWhenEmptyText()
    {
        $this->expectException(DomainException::class);

        new Text('');
    }
}
