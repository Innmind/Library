<?php

namespace APIBundle\Tests\EventListener;

use APIBundle\EventListener\CitationBuildListener;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Node\Citation;
use APIBundle\Graph\Relationship\CitedIn;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Neo4j\ONM\Repository;

class CitationBuildListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $l;

    public function setUp()
    {
        $this->l = new CitationBuildListener(
            $this->getMock(EntityManagerInterface::class)
        );
    }

    public function testSubscribedEvents()
    {
        $this->assertSame(
            [Events::RELATIONSHIP_BUILD => [['replaceCitation', 50]]],
            CitationBuildListener::getSubscribedEvents()
        );
    }

    public function testReplaceCitation()
    {
        $repo = $this
            ->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repo
            ->method('__call')
            ->willReturn($wished = new Citation);
        $em = $this->getMock(EntityManagerInterface::class);
        $em
            ->method('getRepository')
            ->willReturn($repo);
        $l = new CitationBuildListener($em);
        $rel = new CitedIn;
        $rel->setCitation(new Citation);
        $e = new RelationshipBuildEvent($rel);

        $this->assertSame(null, $l->replaceCitation($e));
        $this->assertSame($wished, $rel->getCitation());
    }

    public function testDoesntReplaceCitation()
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
        $l = new CitationBuildListener($em);
        $rel = new CitedIn;
        $rel->setCitation($actual = new Citation);
        $e = new RelationshipBuildEvent($rel);

        $this->assertSame(null, $l->replaceCitation($e));
        $this->assertSame($actual, $rel->getCitation());

        $e = new RelationshipBuildEvent(new \stdClass);

        $this->assertSame(null, $l->replaceCitation($e));
    }
}
