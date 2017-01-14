<?php
declare(strict_types = 1);

namespace Tests\AppBundle\EventListener;

use AppBundle\EventListener\ExceptionListener;
use Domain\Exception\AuthorAlreadyExistException;
use Innmind\Http\Exception\Http\ConflictException;
use Symfony\Component\{
    HttpKernel\HttpKernelInterface,
    HttpKernel\KernelEvents,
    HttpKernel\Event\GetResponseForExceptionEvent,
    EventDispatcher\EventSubscriberInterface,
    HttpFoundation\Request
};

class ExceptionListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            EventSubscriberInterface::class,
            new ExceptionListener
        );
    }

    public function testGetSubscribedEvents()
    {
        $this->assertSame(
            [KernelEvents::EXCEPTION => 'transform'],
            ExceptionListener::getSubscribedEvents()
        );
    }

    public function testDoesntTransform()
    {
        $listener = new ExceptionListener;
        $event = new GetResponseForExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            new Request,
            HttpKernelInterface::MASTER_REQUEST,
            $expected = new \Exception
        );

        $this->assertNull($listener->transform($event));
        $this->assertSame($expected, $event->getException());
    }

    public function testTransformAuthorAlreadyExist()
    {
        $listener = new ExceptionListener;
        $event = new GetResponseForExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            new Request,
            HttpKernelInterface::MASTER_REQUEST,
            $expected = new AuthorAlreadyExistException
        );

        $this->assertNull($listener->transform($event));
        $this->assertInstanceOf(
            ConflictException::class,
            $event->getException()
        );
        $this->assertSame($expected, $event->getException()->getPrevious());
    }
}
