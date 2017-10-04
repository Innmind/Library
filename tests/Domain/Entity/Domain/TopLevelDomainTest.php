<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Domain;

use Domain\Entity\Domain\TopLevelDomain;
use PHPUnit\Framework\TestCase;

class TopLevelDomainTest extends TestCase
{
    public function testInterface()
    {
        $this->assertSame('foo', (string) new TopLevelDomain('foo'));
    }

    /**
     * @expectedException Domain\Exception\DomainException
     */
    public function testThrowWhenEmptyTopLevelDomain()
    {
        new TopLevelDomain('');
    }
}
