<?php
declare(strict_types = 1);

namespace AppBundle\EventListener;

use Domain\Exception\AuthorAlreadyExistException;
use Innmind\Http\Exception\Http\ConflictException;
use Symfony\Component\{
    EventDispatcher\EventSubscriberInterface,
    HttpKernel\KernelEvents,
    HttpKernel\Event\GetResponseForExceptionEvent
};

final class ExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['transform', 200]],
        ];
    }

    public function transform(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();

        switch (true) {
            case $exception instanceof AuthorAlreadyExistException:
                $event->setException(
                    new ConflictException(
                        '',
                        0,
                        $exception
                    )
                );
                break;
        }
    }
}
