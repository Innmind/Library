<?php

namespace APIBundle\Tests\Graph\Relationship;

use APIBundle\Graph\Relationship\Canonical;
use APIBundle\Graph\Relationship\TargetableInterface;
use APIBundle\Graph\Node\HttpResource;

class CanonicalTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $c = new Canonical;

        $this->assertInstanceOf(TargetableInterface::class, $c);
        $this->assertSame($c, $c->setSource($r = new HttpResource));
        $this->assertSame($r, $c->getSource());
        $this->assertSame($c, $c->setDestination($r = new HttpResource));
        $this->assertSame($r, $c->getDestination());
        $this->assertSame($c, $c->setUrl('fr'));
        $this->assertSame('fr', $c->getUrl());
        $this->assertSame($c->getSource(), $c->getTarget());
    }
}