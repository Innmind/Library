<?php

namespace APIBundle\Tests\Graph\Node;

use APIBundle\Graph\Node\Host;

class HostTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $h = new Host;

        $this->assertSame($h, $h->setHost('foo.foo.fr'));
        $this->assertSame('foo.foo.fr', $h->getHost());
    }
}
