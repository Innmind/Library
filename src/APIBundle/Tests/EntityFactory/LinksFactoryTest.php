<?php

namespace APIBundle\Tests\EntityFactory;

use APIBundle\EntityFactory\LinksFactory;
use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Node\HttpResource;
use APIBundle\Graph\Relationship\Referrer;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResource as ServerResource;
use Symfony\Component\EventDispatcher\EventDispatcher;

class LinksFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $f;
    protected $d;

    public function setUp()
    {
        $this->f = new LinksFactory(
            $this->d = new EventDispatcher
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(EntityFactoryInterface::class, $this->f);
    }

    public function testSupports()
    {
        $r = new ServerResource;

        $this->assertFalse($this->f->supports($r));
        $r->set('links', []);
        $this->assertFalse($this->f->supports($r));
        $r->set('links', ['foo']);
        $this->assertTrue($this->f->supports($r));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Expecting an entity of type APIBundle\Graph\Node\Html
     */
    public function testThrowWhenGivingInvalidEntity()
    {
        $this->f->build(new ServerResource, '');
    }

    public function testBuild()
    {
        $r = new ServerResource;
        $r->set('links', ['foo']);
        $e = new Html;
        $fired = false;
        $this->d->addListener(
            Events::RELATIONSHIP_BUILD,
            function (RelationshipBuildEvent $event) use (&$fired, $e) {
                $fired = true;
                $rel = $event->getRelationship();
                $this->assertInstanceOf(Referrer::class, $rel);
                $this->assertInstanceOf(HttpResource::class, $rel->getDestination());
                $this->assertSame($e, $rel->getSource());
                $this->assertSame('foo', $rel->getUrl());
            }
        );
        $this->assertSame(null, $this->f->build($r, $e));
        $this->assertTrue($fired);
    }
}
