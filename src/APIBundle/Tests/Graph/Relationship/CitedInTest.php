<?php

namespace APIBundle\Tests\Graph\Relationship;

use APIBundle\Graph\Relationship\CitedIn;
use APIBundle\Graph\Node\HttpResource;
use APIBundle\Graph\Node\Citation;

class CitedInTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $ci = new CitedIn;

        $this->assertSame($ci, $ci->setCitation($c = new Citation));
        $this->assertSame($c, $ci->getCitation());
        $this->assertSame($ci, $ci->setResource($r = new HttpResource));
        $this->assertSame($r, $ci->getResource());
        $this->assertSame($ci, $ci->setDate($d = new \Datetime));
        $this->assertSame($d, $ci->getDate());
    }
}
