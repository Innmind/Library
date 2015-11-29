<?php

namespace APIBundle\Tests\EntityFactory;

use APIBundle\EntityFactory\CitationsFactory;
use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Node\Citation;
use APIBundle\Graph\Relationship\CitedIn;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResource;
use Symfony\Component\EventDispatcher\EventDispatcher;

class CitationsFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $f;
    protected $d;

    public function setUp()
    {
        $this->f= new CitationsFactory(
            $this->d = new EventDispatcher
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(EntityFactoryInterface::class, $this->f);
    }

    public function testSupports()
    {
        $r = new HttpResource;

        $this->assertFalse($this->f->supports($r));
        $r->set('citations', []);
        $this->assertFalse($this->f->supports($r));
        $r->set('citations', ['foo']);
        $this->assertTrue($this->f->supports($r));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Expecting an entity of type APIBundle\Graph\Node\Html
     */
    public function testThrowWhenGivingInvalidEntity()
    {
        $this->f->build(new HttpResource, '');
    }

    public function testBuild()
    {
        $r = new HttpResource;
        $r->set('citations', ['foo']);
        $e = new Html;
        $fired = false;
        $this->d->addListener(
            Events::RELATIONSHIP_BUILD,
            function (RelationshipBuildEvent $event) use (&$fired, $e) {
                $fired = true;
                $rel = $event->getRelationship();
                $this->assertInstanceOf(CitedIn::class, $rel);
                $this->assertInstanceOf(Citation::class, $rel->getCitation());
                $this->assertSame('foo', $rel->getCitation()->getText());
                $this->assertSame($e, $rel->getResource());
                $this->assertInstanceOf(\DateTime::class, $rel->getDate());
            }
        );
        $this->assertSame(null, $this->f->build($r, $e));
        $this->assertTrue($fired);
    }
}
