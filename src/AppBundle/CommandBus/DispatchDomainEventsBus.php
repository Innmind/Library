<?php
declare(strict_types = 1);

namespace AppBundle\CommandBus;

use Innmind\CommandBus\CommandBusInterface;
use Innmind\EventBus\{
    EventBusInterface,
    ContainsRecordedEventsInterface
};
use Innmind\Neo4j\ONM\{
    Entity\Container,
    IdentityInterface
};
use Innmind\Immutable\Stream;

final class DispatchDomainEventsBus implements CommandBusInterface
{
    private $commandBus;
    private $eventBus;
    private $entityContainer;

    public function __construct(
        CommandBusInterface $commandBus,
        EventBusInterface $eventBus,
        Container $entityContainer
    ) {
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->entityContainer = $entityContainer;
    }

    public function handle($command)
    {
        $this->commandBus->handle($command);
        $this
            ->entityContainer
            ->state(Container::STATE_NEW)
            ->merge($this->entityContainer->state(Container::STATE_MANAGED))
            ->merge($this->entityContainer->state(Container::STATE_TO_BE_REMOVED))
            ->merge($this->entityContainer->state(Container::STATE_REMOVED))
            ->filter(function(IdentityInterface $identity, $entity): bool {
                return $entity instanceof ContainsRecordedEventsInterface;
            })
            ->reduce(
                new Stream('object'),
                function(
                    Stream $carry,
                    IdentityInterface $identity,
                    ContainsRecordedEventsInterface $entity
                ): Stream {
                    return $carry->append($entity->recordedEvents());
                }
            )
            ->foreach(function($event): void {
                $this->eventBus->dispatch($event);
            });
    }
}
