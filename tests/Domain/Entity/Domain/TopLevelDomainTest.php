<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Domain;

use Domain\{
    Entity\Domain\TopLevelDomain,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;

class TopLevelDomainTest extends TestCase
{
    public function testInterface()
    {
        $this->assertSame('foo', (string) new TopLevelDomain('foo'));
    }

    public function testThrowWhenEmptyTopLevelDomain()
    {
        $this->expectException(DomainException::class);

        new TopLevelDomain('');
    }
}
