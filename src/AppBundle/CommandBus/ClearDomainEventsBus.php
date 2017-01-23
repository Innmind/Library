<?php
declare(strict_types = 1);

namespace AppBundle\CommandBus;

use Innmind\CommandBus\CommandBusInterface;
use Innmind\EventBus\ContainsRecordedEventsInterface;
use Innmind\Neo4j\ONM\{
    Entity\Container,
    IdentityInterface
};
use Innmind\Immutable\Sequence;

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
            ->state(Container::STATE_NEW)
            ->merge($this->entityContainer->state(Container::STATE_MANAGED))
            ->merge($this->entityContainer->state(Container::STATE_TO_BE_REMOVED))
            ->merge($this->entityContainer->state(Container::STATE_REMOVED))
            ->filter(function(IdentityInterface $identity, $entity): bool {
                return $entity instanceof ContainsRecordedEventsInterface;
            })
            ->foreach(function(
                IdentityInterface $identity,
                ContainsRecordedEventsInterface $entity
            ): void {
                $entity->clearEvents();
            });
    }
}
