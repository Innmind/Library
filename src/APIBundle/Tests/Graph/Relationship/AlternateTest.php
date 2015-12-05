<?php

namespace APIBundle\Tests\Graph\Relationship;

use APIBundle\Graph\Relationship\Alternate;
use APIBundle\Graph\Relationship\TargetableInterface;
use APIBundle\Graph\Node\HttpResource;

class AlternateTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $a = new Alternate;

        $this->assertInstanceOf(TargetableInterface::class, $a);
        $this->assertSame($a, $a->setSource($r = new HttpResource));
        $this->assertSame($r, $a->getSource());
        $this->assertSame($a, $a->setDestination($r = new HttpResource));
        $this->assertSame($r, $a->getDestination());
        $this->assertSame($a, $a->setDate($d = new \Datetime));
        $this->assertSame($d, $a->getDate());
        $this->assertSame($a, $a->setLanguage('fr'));
        $this->assertSame('fr', $a->getLanguage());
        $this->assertSame($a, $a->setUrl('fr'));
        $this->assertSame('fr', $a->getUrl());
        $this->assertSame($a->getSource(), $a->getTarget());
    }
}
