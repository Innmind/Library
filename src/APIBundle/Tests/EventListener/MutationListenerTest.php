<?php

namespace APIBundle\Tests\EventListener;

use APIBundle\EventListener\MutationListener;
use Innmind\Rest\Server\Events;
use Innmind\Rest\Server\Event\Storage\PreUpdateEvent;
use Innmind\Rest\Server\Definition\ResourceDefinition;
use Innmind\Rest\Server\HttpResource;
use Innmind\Neo4j\ONM\UnitOfWork;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Neo4j\ONM\Exception\EntityNotFoundException;
use Innmind\Neo4j\ONM\MetadataRegistry;
use Innmind\Neo4j\ONM\Repository;
use Innmind\Neo4j\ONM\QueryBuilder;
use Innmind\Neo4j\ONM\Mapping\NodeMetadata;
use Innmind\Neo4j\ONM\Mapping\Id;

class MutationListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testSubscribedEvents()
    {
        $this->assertSame(
            [Events::STORAGE_PRE_UPDATE => 'mutateNode'],
            MutationListener::getSubscribedEvents()
        );
    }

    public function testMutateNode()
    {
        $queryExecuted = false;
        $uow = $this
            ->getMockBuilder(UnitOfWork::class)
            ->disableOriginalConstructor()
            ->getMock();
        $uow
            ->method('execute')
            ->will($this->returnCallback(function($query) use (&$queryExecuted) {
                $queryExecuted = true;
                $this->assertSame(
                    'MATCH (node:bar {uuid: {node_match_props}.uuid}) SET node :Bar:Baz;',
                    $query->getCypher()
                );
                $this->assertSame(
                    ['node_match_props' => ['uuid' => 24]],
                    $query->getParameters()
                );
                $this->assertSame(
                    ['node_match_props' => ['uuid' => 'node.uuid']],
                    $query->getReferences()
                );
                $this->assertSame(['node' => 'bar'], $query->getVariables());
            }));
        $mr = new MetadataRegistry;
        $mr
            ->addMetadata(
                (new NodeMetadata)
                    ->setClass('foo')
                    ->addLabel('bar')
                    ->addLabel('baz')
            )
            ->addMetadata(
                (new NodeMetadata)
                    ->setClass('bar')
                    ->addLabel('foobar')
                    ->setId(
                        (new Id)
                            ->setProperty('uuid')
                    )
            );
        $uow
            ->method('getMetadataRegistry')
            ->willReturn($mr);
        $em = $this->getMock(EntityManagerInterface::class);
        $em
            ->method('getUnitOfWork')
            ->willReturn($uow);
        $em
            ->method('find')
            ->will($this->returnCallback(function($class, $id) {
                if ($id === 24 && $class === 'foo') {
                    throw new EntityNotFoundException;
                }
            }));
        $repo = $this
            ->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repo
            ->method('getQueryBuilder')
            ->will($this->returnCallback(function() {
                return new QueryBuilder;
            }));
        $em
            ->method('getRepository')
            ->willReturn($repo);
        $l = new MutationListener($em);
        $definition = new ResourceDefinition('foo');

        $event = new PreUpdateEvent(
            (new HttpResource)->setDefinition($definition),
            42
        );
        $this->assertSame(null, $l->mutateNode($event));
        $this->assertFalse($queryExecuted);

        $definition->addOption('class', 'foo');
        $this->assertSame(null, $l->mutateNode($event));
        $this->assertFalse($queryExecuted);

        $definition->addOption('mutable_from', 'bar');
        $this->assertSame(null, $l->mutateNode($event));
        $this->assertFalse($queryExecuted);

        $definition->addOption('mutable_from', []);
        $this->assertSame(null, $l->mutateNode($event));
        $this->assertFalse($queryExecuted);

        $definition->addOption('mutable_from', ['bar']);
        $this->assertSame(null, $l->mutateNode($event));
        $this->assertFalse($queryExecuted);

        $event = new PreUpdateEvent(
            (new HttpResource)->setDefinition($definition),
            24
        );
        $this->assertSame(null, $l->mutateNode($event));
        $this->assertTrue($queryExecuted);
    }
}
