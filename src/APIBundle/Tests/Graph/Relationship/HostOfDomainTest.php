<?php

namespace APIBundle\Tests\Graph\Relationship;

use APIBundle\Graph\Relationship\HostOfDomain;
use APIBundle\Graph\Node\Host;
use APIBundle\Graph\Node\Domain;

class HostOfDomainTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $hod = new HostOfDomain;

        $this->assertSame($hod, $hod->setHost($h = new Host));
        $this->assertSame($h, $hod->getHost());
        $this->assertSame($hod, $hod->setDomain($d = new Domain));
        $this->assertSame($d, $hod->getDomain());
    }
}
