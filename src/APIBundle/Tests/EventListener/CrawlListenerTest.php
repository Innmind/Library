<?php

namespace APIBundle\Tests\EventListener;

use APIBundle\EventListener\CrawlListener;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Relationship\TargetableInterface;
use APIBundle\Graph\Node\HttpResource;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class CrawlListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testSubscribedEvents()
    {
        $this->assertSame(
            [
                Events::RELATIONSHIP_BUILD => ['register', -20],
                KernelEvents::TERMINATE => 'publish',
            ],
            CrawlListener::getSubscribedEvents()
        );
    }

    /**
     * @dataProvider invalid
     */
    public function testDoesntCrawl($relationship)
    {
        $fired = false;
        $l = new CrawlListener($p = $this->getMock(ProducerInterface::class));
        $p
            ->method('publish')
            ->will($this->returnCallback(function() use (&$fired) {
                $fired = true;
            }));
        $build = new RelationshipBuildEvent($relationship);

        $this->assertSame(null, $l->register($build));

        $terminate = new PostResponseEvent(
            $this->getMock(HttpKernelInterface::class),
            $r = new Request,
            new Response
        );
        $r->headers->set('HOST', 'innmind.io');

        $this->assertSame(null, $l->publish($terminate));
        $this->assertFalse($fired);
    }

    public function testCrawl()
    {
        $relationship = $this->getMock(TargetableInterface::class);
        $relationship
            ->method('getUrl')
            ->willReturn('foo');
        $relationship
            ->method('getTarget')
            ->willReturn($hr = $this->getMock(HttpResource::class));
        $hr
            ->method('getUuid')
            ->willReturn('42');
        $fired = false;
        $count = 0;
        $l = new CrawlListener($p = $this->getMock(ProducerInterface::class));
        $p
            ->method('publish')
            ->will($this->returnCallback(function($msg) use (&$fired, $relationship, &$count) {
                $fired = true;
                $count++;
                $data = unserialize($msg);
                $expected = [
                    'url' => $relationship->getUrl(),
                    'uuid' => $relationship->getTarget()->getUuid(),
                    'host' => 'innmind.io',
                ];

                $this->assertSame($expected, $data);
            }));
        $build = new RelationshipBuildEvent($relationship);
        $r2 = $this->getMock(TargetableInterface::class);
        $r2
            ->method('getUrl')
            ->willReturn('foo');
        $r2
            ->method('getTarget')
            ->willReturn($hr = $this->getMock(HttpResource::class));
        $hr
            ->method('getUuid')
            ->willReturn('42');

        $this->assertSame(null, $l->register($build));
        $l->register(new RelationshipBuildEvent($r2));

        $terminate = new PostResponseEvent(
            $this->getMock(HttpKernelInterface::class),
            $r = new Request,
            new Response
        );
        $r->headers->set('HOST', 'innmind.io');

        $this->assertSame(null, $l->publish($terminate));
        $this->assertTrue($fired);
        $this->assertSame(2, $count);
    }

    public function invalid()
    {
        $mock = $this->getMock(TargetableInterface::class);
        $mock
            ->method('getUrl')
            ->willReturn(null);

        return [
            [new \stdClass],
            [$mock],
        ];
    }
}
