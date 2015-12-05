<?php

namespace APIBundle\Tests\Graph\Relationship;

use APIBundle\Graph\Relationship\PageImage;
use APIBundle\Graph\Relationship\TargetableInterface;
use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Node\Image;

class PageImageTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $pi = new PageImage;

        $this->assertInstanceOf(TargetableInterface::class, $pi);
        $this->assertSame($pi, $pi->setImage($i = new Image));
        $this->assertSame($i, $pi->getImage());
        $this->assertSame($pi, $pi->setPage($h = new Html));
        $this->assertSame($h, $pi->getPage());
        $this->assertSame($pi, $pi->setDate($d = new \Datetime));
        $this->assertSame($d, $pi->getDate());
        $this->assertSame($pi, $pi->setDescription('foo'));
        $this->assertSame('foo', $pi->getDescription());
        $this->assertSame($pi, $pi->setUrl('foo.fr'));
        $this->assertSame('foo.fr', $pi->getUrl());
        $this->assertSame($pi->getImage(), $pi->getTarget());
    }
}
