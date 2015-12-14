<?php

namespace APIBundle\Tests\Graph\Relationship;

use APIBundle\Graph\Relationship\Referrer;
use APIBundle\Graph\Relationship\TargetableInterface;
use APIBundle\Graph\Node\HttpResource;

class ReferrerTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $c = new Referrer;

        $this->assertInstanceOf(TargetableInterface::class, $c);
        $this->assertSame($c, $c->setSource($r = new HttpResource));
        $this->assertSame($r, $c->getSource());
        $this->assertSame($c, $c->setDestination($r = new HttpResource));
        $this->assertSame($r, $c->getDestination());
        $this->assertSame($c, $c->setUrl('fr'));
        $this->assertTrue($c->hasUrl());
        $this->assertSame('fr', $c->getUrl());
        $this->assertSame($c->getDestination(), $c->getTarget());
        $this->assertSame($c, $c->removeUrl());
        $this->assertFalse($c->hasUrl());
    }
}
