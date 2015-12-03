<?php

namespace APIBundle\Tests\Graph\Node;

use APIBundle\Graph\Node\Domain;

class DomainTest extends \PHPUnit_Framework_testCase
{
    public function testEntity()
    {
        $d = new Domain;

        $this->assertSame($d, $d->setDomain('foo.fr'));
        $this->assertSame('foo.fr', $d->getDomain());
        $this->assertSame($d, $d->setTld('fr'));
        $this->assertSame('fr', $d->getTld());
    }
}
