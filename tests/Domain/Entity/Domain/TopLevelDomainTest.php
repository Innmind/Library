<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\Domain;

use Domain\Entity\Domain\TopLevelDomain;

class TopLevelDomainTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertSame('foo', (string) new TopLevelDomain('foo'));
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyTopLevelDomain()
    {
        new TopLevelDomain('');
    }
}
