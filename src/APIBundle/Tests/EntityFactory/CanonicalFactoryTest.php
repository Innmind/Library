<?php

namespace APIBundle\Tests\EntityFactory;

use APIBundle\EntityFactory\CanonicalFactory;
use APIBundle\EntityFactoryInterface;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Node\HttpResource;
use APIBundle\Graph\Relationship\Canonical;
use Innmind\Rest\Server\HttpResource as ServerResource;
use Symfony\Component\EventDispatcher\EventDispatcher;

class CanonicalFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $f;
    protected $d;

    public function setUp()
    {
        $this->f = new CanonicalFactory(
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
        $r->set('canonical', 'foo');
        $this->assertTrue($this->f->supports($r));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Expecting an entity of type APIBundle\Graph\Node\HttpResource
     */
    public function testThrowWhenGivingInvalidEntity()
    {
        $this->f->build(new ServerResource, '');
    }

    public function testBuild()
    {
        $r = new ServerResource;
        $r->set('canonical', 'foo');
        $e = new HttpResource;
        $fired = false;
        $this->d->addListener(
            Events::RELATIONSHIP_BUILD,
            function (RelationshipBuildEvent $event) use (&$fired, $e) {
                $fired = true;
                $rel = $event->getRelationship();
                $this->assertInstanceOf(Canonical::class, $rel);
                $this->assertInstanceOf(HttpResource::class, $rel->getSource());
                $this->assertSame($e, $rel->getDestination());
                $this->assertSame('foo', $rel->getUrl());
            }
        );
        $this->assertSame(null, $this->f->build($r, $e));
        $this->assertTrue($fired);
    }
}
