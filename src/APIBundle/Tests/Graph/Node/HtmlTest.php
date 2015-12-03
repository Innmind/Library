<?php

namespace APIBundle\Tests\Graph\Node;

use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Node\HttpResource;

class HtmlTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $h = new Html;

        $this->assertInstanceOf(HttpResource::class, $h);
        $this->assertSame($h, $h->setContent('foo'));
        $this->assertSame('foo', $h->getContent());
        $this->assertSame($h, $h->setDescription('foo'));
        $this->assertSame('foo', $h->getDescription());
        $this->assertSame($h, $h->setAnchors(['foo']));
        $this->assertSame(['foo'], $h->getAnchors());
        $this->assertSame($h, $h->setJournal(true));
        $this->assertTrue($h->isJournal());
        $this->assertSame($h, $h->setJournal(false));
        $this->assertFalse($h->isJournal());
        $this->assertSame($h, $h->setThemeColor([10, 10, 10]));
        $this->assertSame([10, 10, 10], $h->getThemeColor());
        $this->assertSame($h, $h->setTitle('foo'));
        $this->assertSame('foo', $h->getTitle());
        $this->assertSame($h, $h->setRss('foo'));
        $this->assertSame('foo', $h->getRss());
        $this->assertSame($h, $h->setAndroid('foo'));
        $this->assertSame('foo', $h->getAndroid());
        $this->assertSame($h, $h->setIos('foo'));
        $this->assertSame('foo', $h->getIos());
    }
}
