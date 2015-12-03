<?php

namespace APIBundle\Tests\EventListener;

use APIBundle\EventListener\HostBuildListener;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Node\Host;
use APIBundle\Graph\Relationship\ResourceOfHost;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Neo4j\ONM\Repository;

class HostBuildListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $l;

    public function setUp()
    {
        $this->l = new HostBuildListener(
            $this->getMock(EntityManagerInterface::class)
        );
    }

    public function testSubscribedEvents()
    {
        $this->assertSame(
            [Events::RELATIONSHIP_BUILD => [['replaceHost', 50]]],
            HostBuildListener::getSubscribedEvents()
        );
    }

    public function testReplaceHost()
    {
        $repo = $this
            ->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repo
            ->method('__call')
            ->willReturn($wished = new Host);
        $em = $this->getMock(EntityManagerInterface::class);
        $em
            ->method('getRepository')
            ->willReturn($repo);
        $l = new HostBuildListener($em);
        $rel = new ResourceOfHost;
        $rel->setHost(new Host);
        $e = new RelationshipBuildEvent($rel);

        $this->assertSame(null, $l->replaceHost($e));
        $this->assertSame($wished, $rel->getHost());
    }

    public function testDoesntReplaceHost()
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
        $l = new HostBuildListener($em);
        $rel = new ResourceOfHost;
        $rel->setHost($actual = new Host);
        $e = new RelationshipBuildEvent($rel);

        $this->assertSame(null, $l->replaceHost($e));
        $this->assertSame($actual, $rel->getHost());

        $e = new RelationshipBuildEvent(new \stdClass);

        $this->assertSame(null, $l->replaceHost($e));
    }
}
