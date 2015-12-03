<?php

namespace APIBundle\Tests\EntityFactory;

use APIBundle\EntityFactory\ImagesFactory;
use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Node\Image;
use APIBundle\Graph\Relationship\PageImage;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResource;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ImagesFatoryTest extends \PHPUnit_Framework_TestCase
{
    protected $f;
    protected $d;

    public function setUp()
    {
        $this->f = new ImagesFactory(
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
        $r->set('images', []);
        $this->assertFalse($this->f->supports($r));
        $r->set('images', ['foo']);
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
        $r->set('images', [
            (new HttpResource)
                ->set('url', 'foo')
                ->set('description', 'bar')
        ]);
        $e = new Html;
        $fired = false;
        $this->d->addListener(
            Events::RELATIONSHIP_BUILD,
            function (RelationshipBuildEvent $event) use (&$fired, $e) {
                $fired = true;
                $rel = $event->getRelationship();
                $this->assertInstanceOf(PageImage::class, $rel);
                $this->assertInstanceOf(Image::class, $rel->getImage());
                $this->assertSame('foo', $rel->getUrl());
                $this->assertSame('bar', $rel->getDescription());
                $this->assertSame($e, $rel->getPage());
                $this->assertInstanceOf(\DateTime::class, $rel->getDate());
            }
        );
        $this->assertSame(null, $this->f->build($r, $e));
        $this->assertTrue($fired);
    }
}
