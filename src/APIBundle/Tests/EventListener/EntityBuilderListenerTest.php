<?php

namespace APIBundle\Tests\EventListener;

use APIBundle\EventListener\EntityBuilderListener;
use APIBundle\EntityFactoryInterface;
use Innmind\Rest\Server\HttpResource;
use Innmind\Rest\Server\Events;
use Innmind\Rest\Server\Event\EntityBuildEvent;

class EntityBuilderListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $l;

    public function setUp()
    {
        $this->l = new EntityBuilderListener(
            $this->getMock(EntityFactoryInterface::class)
        );
    }

    public function testSubscribedEvents()
    {
        $this->assertSame(
            [Events::ENTITY_BUILD => 'buildEntity'],
            EntityBuilderListener::getSubscribedEvents()
        );
    }

    public function testBuildEntity()
    {
        $l = new EntityBuilderListener(
            $f = $this->getMock(EntityFactoryInterface::class)
        );
        $fired = false;
        $f
            ->method('supports')
            ->willReturn(true);
        $f
            ->method('build')
            ->will($this->returnCallback(function () use(&$fired)  {
                $fired = true;
            }));
        $e = new EntityBuildEvent(new HttpResource, new \stdClass);
        $this->assertSame(null, $l->buildEntity($e));
        $this->assertTrue($fired);
    }

    public function testDoesntBuildEntity()
    {
        $l = new EntityBuilderListener(
            $f = $this->getMock(EntityFactoryInterface::class)
        );
        $fired = false;
        $f
            ->method('supports')
            ->willReturn(false);
        $f
            ->method('build')
            ->will($this->returnCallback(function () use(&$fired)  {
                $fired = true;
            }));
        $e = new EntityBuildEvent(new HttpResource, new \stdClass);
        $this->assertSame(null, $l->buildEntity($e));
        $this->assertFalse($fired);
    }
}
