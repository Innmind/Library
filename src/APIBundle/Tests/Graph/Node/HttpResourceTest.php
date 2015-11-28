<?php

namespace APIBundle\Tests\Graph\Node;

use APIBundle\Graph\Node\HttpResource;

class HttpResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $r = new HttpResource;

        $this->assertSame($r, $r->setScheme('https'));
        $this->assertSame('https', $r->getScheme());
        $this->assertSame($r, $r->setPort('80'));
        $this->assertSame(80, $r->getPort());
        $this->assertSame($r, $r->setPath('/foo'));
        $this->assertSame('/foo', $r->getPath());
        $this->assertSame($r, $r->setQuery('bar=baz'));
        $this->assertSame('bar=baz', $r->getQuery());
        $this->assertSame($r, $r->setCharset('utf-8'));
        $this->assertSame('utf-8', $r->getCharset());
    }
}
