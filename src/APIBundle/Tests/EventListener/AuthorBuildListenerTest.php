<?php

namespace APIBundle\Tests\EventListener;

use APIBundle\EventListener\AuthorBuildListener;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Node\Author;
use APIBundle\Graph\Relationship\PageAuthor;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Neo4j\ONM\Repository;

class AuthorBuildListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $l;

    public function setUp()
    {
        $this->l = new AuthorBuildListener(
            $this->getMock(EntityManagerInterface::class)
        );
    }

    public function testSubscribedEvents()
    {
        $this->assertSame(
            [Events::RELATIONSHIP_BUILD => [['replaceAuthor', 50]]],
            AuthorBuildListener::getSubscribedEvents()
        );
    }

    public function testReplaceAuthor()
    {
        $repo = $this
            ->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repo
            ->method('__call')
            ->willReturn($wished = new Author);
        $em = $this->getMock(EntityManagerInterface::class);
        $em
            ->method('getRepository')
            ->willReturn($repo);
        $l = new AuthorBuildListener($em);
        $rel = new PageAuthor;
        $rel->setAuthor(new Author);
        $e = new RelationshipBuildEvent($rel);

        $this->assertSame(null, $l->replaceAuthor($e));
        $this->assertSame($wished, $rel->getAuthor());
    }

    public function testDoesntReplaceAuthor()
    {
        $repo = $this
            ->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repo
            ->method('__call')
            ->willReturn(null);
        $em = $this->getMock(EntityManagerInterface::class);
        $em
            ->method('getRepository')
            ->willReturn($repo);
        $l = new AuthorBuildListener($em);
        $rel = new PageAuthor;
        $rel->setAuthor($actual = new Author);
        $e = new RelationshipBuildEvent($rel);

        $this->assertSame(null, $l->replaceAuthor($e));
        $this->assertSame($actual, $rel->getAuthor());

        $e = new RelationshipBuildEvent(new \stdClass);

        $this->assertSame(null, $l->replaceAuthor($e));
    }
}
