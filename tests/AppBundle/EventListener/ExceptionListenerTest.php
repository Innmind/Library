<?php
declare(strict_types = 1);

namespace Tests\AppBundle\EventListener;

use AppBundle\EventListener\ExceptionListener;
use Domain\{
    Exception\AuthorAlreadyExistException,
    Entity\Author,
    Entity\Author\IdentityInterface as AuthorIdentity,
    Entity\Author\Name
};
use Innmind\Http\Exception\Http\ConflictException;
use Innmind\Immutable\Map;
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
                    AuthorAlreadyExistException::class,
                    ConflictException::class
                )
        );
        $event = new GetResponseForExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            new Request,
            HttpKernelInterface::MASTER_REQUEST,
            $expected = new AuthorAlreadyExistException(
                new Author(
                    $this->createMock(AuthorIdentity::class),
                    new Name('foo')
                )
            )
        );

        $this->assertNull($listener->transform($event));
        $this->assertInstanceOf(
            ConflictException::class,
            $event->getException()
        );
        $this->assertSame($expected, $event->getException()->getPrevious());
    }

    /**
     * @expectedException AppBundle\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidMap()
    {
        new ExceptionListener(new Map('string', 'object'));
    }
}
