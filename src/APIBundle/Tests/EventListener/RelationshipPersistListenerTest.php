<?php

namespace APIBundle\Tests\EventListener;

use APIBundle\EventListener\RelationshipPersistListener;
use APIBundle\Events as ApiEvents;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Rest\Server\Events;
use Innmind\Rest\Server\HttpResource;
use Innmind\Rest\Server\Definition\ResourceDefinition;
use Innmind\Rest\Server\Event\Storage\PreCreateEvent;

class RelationshipPersistListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $l;

    public function setUp()
    {
        $this->l = new RelationshipPersistListener(
            $this->getMock(EntityManagerInterface::class)
        );
    }

    public function testSubscribedEvents()
    {
        $this->assertSame(
            [
                Events::STORAGE_PRE_CREATE => 'enable',
                Events::STORAGE_POST_CREATE => 'disable',
                ApiEvents::RELATIONSHIP_BUILD => 'persist',
            ],
            RelationshipPersistListener::getSubscribedEvents()
        );
    }

    public function testPersist()
    {
        $fired = false;
        $em = $this->getMock(EntityManagerInterface::class);
        $em
            ->method('persist')
            ->will($this->returnCallback(function ($e) use (&$fired) {
                $fired = true;
                $this->assertInstanceOf('stdClass', $e);
            }));
        $resource = new HttpResource;
        $resource->setDefinition(
            (new ResourceDefinition('foo'))
                ->setStorage('neo4j')
        );
        $event = $this
            ->getMockBuilder(PreCreateEvent::class)
            ->disableOriginalConstructor()
            ->getMock();
        $event
            ->method('getResource')
            ->willReturn($resource);
        $e = new RelationshipBuildEvent(new \stdClass);
        $l = new RelationshipPersistListener($em);

        $this->assertSame(null, $l->enable($event));
        $this->assertSame(null, $l->persist($e));
        $this->assertSame(null, $l->disable());
        $this->assertTrue($fired);
    }

    public function testDoesntPersist()
    {
        $fired = false;
        $em = $this->getMock(EntityManagerInterface::class);
        $em
            ->method('persist')
            ->will($this->returnCallback(function ($e) use (&$fired) {
                $fired = true;
            }));
        $resource = new HttpResource;
        $resource->setDefinition(
            (new ResourceDefinition('foo'))
                ->setStorage('foo')
        );
        $event = $this
            ->getMockBuilder(PreCreateEvent::class)
            ->disableOriginalConstructor()
            ->getMock();
        $event
            ->method('getResource')
            ->willReturn($resource);
        $e = new RelationshipBuildEvent(new \stdClass);
        $l = new RelationshipPersistListener($em);

        $this->assertSame(null, $l->enable($event));
        $this->assertSame(null, $l->persist($e));
        $this->assertSame(null, $l->disable());
        $this->assertFalse($fired);
    }
}
