<?php

namespace APIBundle\Tests\EventListener;

use APIBundle\EventListener\DomainBuildListener;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Node\Domain;
use APIBundle\Graph\Relationship\HostOfDomain;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Neo4j\ONM\Repository;

class DomainBuildListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $l;

    public function setUp()
    {
        $this->l = new DomainBuildListener(
            $this->getMock(EntityManagerInterface::class)
        );
    }

    public function testSubscribedEvents()
    {
        $this->assertSame(
            [Events::RELATIONSHIP_BUILD => [['replaceDomain', 50]]],
            DomainBuildListener::getSubscribedEvents()
        );
    }

    public function testReplaceDomain()
    {
        $repo = $this
            ->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repo
            ->method('findOneBy')
            ->willReturn($wished = new Domain);
        $em = $this->getMock(EntityManagerInterface::class);
        $em
            ->method('getRepository')
            ->willReturn($repo);
        $l = new DomainBuildListener($em);
        $rel = new HostOfDomain;
        $rel->setDomain(new Domain);
        $e = new RelationshipBuildEvent($rel);

        $this->assertSame(null, $l->replaceDomain($e));
        $this->assertSame($wished, $rel->getDomain());
    }

    public function testDoesntReplaceDomain()
    {
        $repo = $this
            ->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repo
            ->method('findOneBy')
            ->willReturn(null);
        $em = $this->getMock(EntityManagerInterface::class);
        $em
            ->method('getRepository')
            ->willReturn($repo);
        $l = new DomainBuildListener($em);
        $rel = new HostOfDomain;
        $rel->setDomain($actual = new Domain);
        $e = new RelationshipBuildEvent($rel);

        $this->assertSame(null, $l->replaceDomain($e));
        $this->assertSame($actual, $rel->getDomain());

        $e = new RelationshipBuildEvent(new \stdClass);

        $this->assertSame(null, $l->replaceDomain($e));
    }
}
