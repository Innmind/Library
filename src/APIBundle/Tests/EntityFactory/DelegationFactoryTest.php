<?php

namespace APIBundle\Tests\EntityFactory;

use APIBundle\EntityFactory\DelegationFactory;
use APIBundle\EntityFactoryInterface;
use Innmind\Rest\Server\HttpResource;

class DelegationFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $f = new DelegationFactory([]);

        $this->assertInstanceOf(EntityFactoryInterface::class, $f);
    }

    public function testSupports()
    {
        $f = new DelegationFactory([]);

        $r = new HttpResource;
        $this->assertFalse($f->supports($r));

        $f = new DelegationFactory([
            $m = $this->getMock(EntityFactoryInterface::class),
        ]);
        $m
            ->method('supports')
            ->willReturn(false);
        $this->assertFalse($f->supports($r));

        $f = new DelegationFactory([
            $m = $this->getMock(EntityFactoryInterface::class),
            $m2 = $this->getMock(EntityFactoryInterface::class),
        ]);
        $m
            ->method('supports')
            ->willReturn(false);
        $m2
            ->method('supports')
            ->willReturn(true);
        $this->assertTrue($f->supports($r));
    }

    public function testBuild()
    {
        $fired = false;
        $f = new DelegationFactory([
            $m = $this->getMock(EntityFactoryInterface::class),
            $m2 = $this->getMock(EntityFactoryInterface::class),
        ]);
        $m
            ->method('supports')
            ->willReturn(false);
        $m
            ->method('build')
            ->will($this->returnCallback(function () {
                throw new \LogicException('The factory should not be called');
            }));
        $m2
            ->method('supports')
            ->willReturn(true);
        $m2
            ->method('build')
            ->will($this->returnCallback(function () use (&$fired) {
                $fired = true;
            }));
        $this->assertSame(null, $f->build(new HttpResource, new \stdClass));
        $this->assertTrue($fired);
    }
}
