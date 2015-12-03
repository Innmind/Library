<?php

namespace APIBundle\Tests\Graph\Node;

use APIBundle\Graph\Node\Image;
use APIBundle\Graph\Node\HttpResource;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $i = new Image;

        $this->assertInstanceOf(HttpResource::class, $i);
        $this->assertSame($i, $i->setWidth('42'));
        $this->assertSame(42, $i->getWidth());
        $this->assertSame($i, $i->setHeight('42'));
        $this->assertSame(42, $i->getHeight());
        $this->assertSame($i, $i->setMime('image/foo'));
        $this->assertSame('image/foo', $i->getMime());
        $this->assertSame($i, $i->setExtension('.jpeg'));
        $this->assertSame('.jpeg', $i->getExtension());
        $this->assertSame($i, $i->setWeight('42'));
        $this->assertSame(42, $i->getWeight());
        $this->assertSame($i, $i->setExif(['extension' => '.jpeg']));
        $this->assertSame(['extension' => '.jpeg'], $i->getExif());
    }
}
