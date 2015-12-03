<?php

namespace APIBundle\Tests\Graph\Relationship;

use APIBundle\Graph\Relationship\ResourceOfHost;
use APIBundle\Graph\Node\HttpResource;
use APIBundle\Graph\Node\Host;

class ResourceOfHostTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $roh = new ResourceOfHost;

        $this->assertSame($roh, $roh->setHost($h = new Host));
        $this->assertSame($h, $roh->getHost());
        $this->assertSame($roh, $roh->setResource($r = new HttpResource));
        $this->assertSame($r, $roh->getResource());
        $this->assertSame($roh, $roh->setDate($d = new \Datetime));
        $this->assertSame($d, $roh->getDate());
    }
}
