<?php

namespace APIBundle\Tests\EntityFactory;

use APIBundle\EntityFactory\AuthorFactory;
use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Node\Author;
use APIBundle\Graph\Relationship\PageAuthor;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResource as ServerResource;
use Symfony\Component\EventDispatcher\EventDispatcher;

class AuthorFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $f;
    protected $d;

    public function setUp()
    {
        $this->f = new AuthorFactory(
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
        $r->set('author', 'meesa');
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
        $r->set('author', 'meesa');
        $e = new Html;
        $fired = false;
        $this->d->addListener(
            Events::RELATIONSHIP_BUILD,
            function (RelationshipBuildEvent $event) use (&$fired, $e) {
                $fired = true;
                $rel = $event->getRelationship();
                $this->assertInstanceOf(PageAuthor::class, $rel);
                $this->assertInstanceOf(Author::class, $rel->getAuthor());
                $this->assertSame('meesa', $rel->getAuthor()->getName());
                $this->assertSame($e, $rel->getPage());
                $this->assertInstanceOf(\DateTime::class, $rel->getDate());
            }
        );
        $this->assertSame(null, $this->f->build($r, $e));
        $this->assertTrue($fired);
    }
}
