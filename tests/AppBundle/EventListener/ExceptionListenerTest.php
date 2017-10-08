<?php
declare(strict_types = 1);

namespace Tests\AppBundle\EventListener;

use AppBundle\EventListener\ExceptionListener;
use Domain\{
    Exception\AuthorAlreadyExist,
    Entity\Author,
    Entity\Author\Identity as AuthorIdentity,
    Entity\Author\Name
};
use Innmind\Http\Exception\Http\Conflict;
use Innmind\Immutable\Map;
use Symfony\Component\{
    HttpKernel\HttpKernelInterface,
    HttpKernel\KernelEvents,
    HttpKernel\Event\GetResponseForExceptionEvent,
    EventDispatcher\EventSubscriberInterface,
    HttpFoundation\Request
};
use PHPUnit\Framework\TestCase;

class ExceptionListenerTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            EventSubscriberInterface::class,
            new ExceptionListener(new Map('string', 'string'))
        );
    }

    public function testGetSubscribedEvents()
    {
        $this->assertSame(
            [KernelEvents::EXCEPTION => [['transform', 200]]],
            ExceptionListener::getSubscribedEvents()
        );
    }

    public function testDoesntTransform()
    {
        $listener = new ExceptionListener(new Map('string', 'string'));
        $event = new GetResponseForExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            new Request,
            HttpKernelInterface::MASTER_REQUEST,
            $expected = new \Exception
        );

        $this->assertNull($listener->transform($event));
        $this->assertSame($expected, $event->getException());
    }

    public function testTransformKnownException()
    {
        $listener = new ExceptionListener(
            (new Map('string', 'string'))
                ->put(
                    AuthorAlreadyExist::class,
                    Conflict::class
                )
        );
        $event = new GetResponseForExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            new Request,
            HttpKernelInterface::MASTER_REQUEST,
            $expected = new AuthorAlreadyExist(
                new Author(
                    $this->createMock(AuthorIdentity::class),
                    new Name('foo')
                )
            )
        );

        $this->assertNull($listener->transform($event));
        $this->assertInstanceOf(
            Conflict::class,
            $event->getException()
        );
        $this->assertSame($expected, $event->getException()->getPrevious());
        $this->assertSame('', $event->getException()->getMessage());
        $this->assertSame(0, $event->getException()->getCode());
    }

    /**
     * @expectedException AppBundle\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidMap()
    {
        new ExceptionListener(new Map('string', 'object'));
    }
}
