<?php

namespace APIBundle\Tests\EventListener;

use APIBundle\EventListener\AlternateBuildListener;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Node\HttpResource;
use APIBundle\Graph\Relationship\Alternate;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Neo4j\ONM\UnitOfWork;
use Pdp\Parser;
use Pdp\PublicSuffixListManager;

class AlternateBuildListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $l;
    protected $p;

    public function setUp()
    {
        $this->l = new AlternateBuildListener(
            $this->getMock(EntityManagerInterface::class),
            $this->p = new Parser((new PublicSuffixListManager)->getList())
        );
    }

    public function testSubscribedEvents()
    {
        $this->assertSame(
            [Events::RELATIONSHIP_BUILD => [['replaceAlternate', 50]]],
            AlternateBuildListener::getSubscribedEvents()
        );
    }

    public function testReplaceAlternate()
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
        $l = new AlternateBuildListener($em, $this->p);
        $rel = new Alternate;
        $rel
            ->setSource(new HttpResource)
            ->setUrl('http://xn--example.com');
        $e = new RelationshipBuildEvent($rel);

        $this->assertSame(null, $l->replaceAlternate($e));
        $this->assertSame($wished, $rel->getSource());
    }

    public function testDoesntReplaceAlternate()
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
        $l = new AlternateBuildListener($em, $this->p);
        $rel = new Alternate;
        $rel
            ->setSource($actual = new HttpResource)
            ->setUrl('http://xn--example.com');
        $e = new RelationshipBuildEvent($rel);

        $this->assertSame(null, $l->replaceAlternate($e));
        $this->assertSame($actual, $rel->getSource());

        $e = new RelationshipBuildEvent(new \stdClass);

        $this->assertSame(null, $l->replaceAlternate($e));
    }
}
