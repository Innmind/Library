<?php

namespace APIBundle\Tests\EventListener;

use APIBundle\EventListener\CanonicalBuildListener;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Node\HttpResource;
use APIBundle\Graph\Relationship\Canonical;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Neo4j\ONM\UnitOfWork;
use Pdp\Parser;
use Pdp\PublicSuffixListManager;

class CanonicalBuildListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $l;
    protected $p;

    public function setUp()
    {
        $this->l = new CanonicalBuildListener(
            $this->getMock(EntityManagerInterface::class),
            $this->p = new Parser((new PublicSuffixListManager)->getList())
        );
    }

    public function testSubscribedEvents()
    {
        $this->assertSame(
            [Events::RELATIONSHIP_BUILD => [['replaceCanonical', 50]]],
            CanonicalBuildListener::getSubscribedEvents()
        );
    }

    public function testReplaceCanonical()
    {
        $resources = new \SplObjectStorage;
        $resources->attach($wished = new HttpResource);
        $resources->rewind();
        $uow = $this
            ->getMockBuilder(UnitOfWork::class)
            ->disableOriginalConstructor()
            ->getMock();
        $uow
            ->method('execute')
            ->willReturn($resources);
        $em = $this->getMock(EntityManagerInterface::class);
        $em
            ->method('getUnitOfWork')
            ->willReturn($uow);
        $l = new CanonicalBuildListener($em, $this->p);
        $rel = new Canonical;
        $rel
            ->setSource(new HttpResource)
            ->setUrl('http://xn--example.com');
        $e = new RelationshipBuildEvent($rel);

        $this->assertSame(null, $l->replaceCanonical($e));
        $this->assertSame($wished, $rel->getSource());
    }

    public function testDoesntReplaceCanonical()
    {
        $resources = new \SplObjectStorage;
        $uow = $this
            ->getMockBuilder(UnitOfWork::class)
            ->disableOriginalConstructor()
            ->getMock();
        $uow
            ->method('execute')
            ->willReturn($resources);
        $em = $this->getMock(EntityManagerInterface::class);
        $em
            ->method('getUnitOfWork')
            ->willReturn($uow);
        $l = new CanonicalBuildListener($em, $this->p);
        $rel = new Canonical;
        $rel
            ->setSource($actual = new HttpResource)
            ->setUrl('http://xn--example.com');
        $e = new RelationshipBuildEvent($rel);

        $this->assertSame(null, $l->replaceCanonical($e));
        $this->assertSame($actual, $rel->getSource());

        $e = new RelationshipBuildEvent(new \stdClass);

        $this->assertSame(null, $l->replaceCanonical($e));
    }
}
