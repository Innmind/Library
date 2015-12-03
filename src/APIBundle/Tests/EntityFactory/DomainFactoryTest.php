<?php

namespace APIBundle\Tests\EntityFactory;

use APIBundle\EntityFactory\DomainFactory;
use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Domain;
use APIBundle\Graph\Node\Host;
use APIBundle\Graph\Relationship\HostOfDomain;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResource;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DomainFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $f;
    protected $d;

    public function setUp()
    {
        $this->f = new DomainFactory(
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
        $r->set('domain', 'foo.fr');
        $this->assertFalse($this->f->supports($r));
        $r->set('tld', 'fr');
        $this->assertTrue($this->f->supports($r));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Expecting an entity of type APIBundle\Graph\Node\Host
     */
    public function testThrowWhenGivingInvalidEntity()
    {
        $this->f->build(new HttpResource, '');
    }

    public function testBuild()
    {
        $r = new HttpResource;
        $r
            ->set('domain', 'foo.fr')
            ->set('tld', 'fr');
        $e = new Host;
        $fired = false;
        $this->d->addListener(
            Events::RELATIONSHIP_BUILD,
            function (RelationshipBuildEvent $event) use (&$fired, $e) {
                $fired = true;
                $rel = $event->getRelationship();
                $this->assertInstanceOf(HostOfDomain::class, $rel);
                $this->assertInstanceOf(Domain::class, $rel->getDomain());
                $this->assertSame('foo.fr', $rel->getDomain()->getDomain());
                $this->assertSame('fr', $rel->getDomain()->getTld());
                $this->assertSame($e, $rel->getHost());
            }
        );
        $this->assertSame(null, $this->f->build($r, $e));
        $this->assertTrue($fired);
    }
}
