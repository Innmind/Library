<?php

namespace APIBundle\Tests\Graph\Node;

use APIBundle\Graph\Node\Author;

class AuthorTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $author = new Author;

        $this->assertSame($author, $author->setName('foo'));
        $this->assertSame('foo', $author->getName());
    }
}
