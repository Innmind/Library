<?php
declare(strict_types = 1);

namespace AppBundle\EventListener;

use AppBundle\Exception\InvalidArgumentException;
use Innmind\Immutable\MapInterface;
use Symfony\Component\{
    EventDispatcher\EventSubscriberInterface,
    HttpKernel\KernelEvents,
    HttpKernel\Event\GetResponseForExceptionEvent
};

final class ExceptionListener implements EventSubscriberInterface
{
    private $map;

    /**
     * @param MapInterface<string, string> $map
     */
    public function __construct(MapInterface $map)
    {
        if (
            (string) $map->keyType() !== 'string' ||
            (string) $map->valueType() !== 'string'
        ) {
            throw new InvalidArgumentException;
        }

        $this->map = $map;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['transform', 200]],
        ];
    }

    public function transform(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();
        $class = get_class($exception);

        if ($this->map->contains($class)) {
            $newException = $this->map->get($class);
            $event->setException(
                new $newException('', 0, $exception)
            );
        }
    }
}
