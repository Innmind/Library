<?php
declare(strict_types = 1);

namespace AppBundle\CommandBus;

use Innmind\CommandBus\CommandBusInterface;
use Innmind\EventBus\ContainsRecordedEventsInterface;
use Innmind\Neo4j\ONM\{
    Entity\Container,
    Entity\Container\State,
    Identity
};

final class ClearDomainEventsBus implements CommandBusInterface
{
    private $commandBus;
    private $entityContainer;

    public function __construct(
        CommandBusInterface $commandBus,
        Container $entityContainer
    ) {
        $this->commandBus = $commandBus;
        $this->entityContainer = $entityContainer;
    }

    public function handle($command)
    {
        $this->commandBus->handle($command);
        $this
            ->entityContainer
            ->state(State::new())
            ->merge($this->entityContainer->state(State::managed()))
            ->merge($this->entityContainer->state(State::toBeRemoved()))
            ->merge($this->entityContainer->state(State::removed()))
            ->filter(function(Identity $identity, $entity): bool {
                return $entity instanceof ContainsRecordedEventsInterface;
            })
            ->foreach(function(
                Identity $identity,
                ContainsRecordedEventsInterface $entity
            ): void {
                $entity->clearEvents();
            });
    }
}
