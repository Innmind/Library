<?php

namespace APIBundle\Tests\Graph\Relationship;

use APIBundle\Graph\Relationship\PageAuthor;
use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Node\Author;

class PageAuthorTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $pa = new PageAuthor;

        $this->assertSame($pa, $pa->setAuthor($a = new Author));
        $this->assertSame($a, $pa->getAuthor());
        $this->assertSame($pa, $pa->setPage($h = new Html));
        $this->assertSame($h, $pa->getPage());
        $this->assertSame($pa, $pa->setDate($d = new \Datetime));
        $this->assertSame($d, $pa->getDate());
    }
}
