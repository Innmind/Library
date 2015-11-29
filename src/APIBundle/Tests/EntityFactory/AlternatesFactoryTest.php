<?php

namespace APIBundle\Tests\EntityFactory;

use APIBundle\EntityFactoryInterface;
use APIBundle\EntityFactory\AlternatesFactory;
use APIBundle\Graph\Node\HttpResource;
use APIBundle\Graph\Relationship\Alternate;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResource as ServerResource;
use Symfony\Component\EventDispatcher\EventDispatcher;

class AlternatesFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $f;
    protected $d;

    public function setUp()
    {
        $this->f = new AlternatesFactory(
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
        $r->set('alternates', []);
        $this->assertFalse($this->f->supports($r));
        $r->set('alternates', [['foo']]);
        $this->assertTrue($this->f->supports($r));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Expecting an entity of type APIBundle\Graph\Node\HttpResource
     */
    public function testThrowWhenInvalidEntityGiven()
    {
        $this->f->build(new ServerResource, new \stdClass);
    }

    public function testBuild()
    {
        $r = new ServerResource;
        $e = new HttpResource;
        $r->set('alternates', [
            (new ServerResource)
                ->set('language', 'en')
                ->set('url', 'somewhere.fr')
        ]);
        $fired = false;
        $this->d->addListener(
            Events::RELATIONSHIP_BUILD,
            function (RelationshipBuildEvent $event) use (&$fired, $e) {
                $fired = true;
                $rel = $event->getRelationship();
                $this->assertInstanceOf(Alternate::class, $rel);
                $this->assertInstanceOf(HttpResource::class, $rel->getSource());
                $this->assertSame($e, $rel->getDestination());
                $this->assertSame('en', $rel->getLanguage());
                $this->assertSame('somewhere.fr', $rel->getUrl());
            }
        );
        $this->assertSame(null, $this->f->build($r, $e));
        $this->assertTrue($fired);
    }
}
