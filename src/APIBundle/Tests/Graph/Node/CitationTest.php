<?php

namespace APIBundle\Tests\Graph\Node;

use APIBundle\Graph\Node\Citation;

class CitationTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $c = new Citation;

        $this->assertSame($c, $c->setText('foo'));
        $this->assertSame('foo', $c->getText());
    }
}
