<?php
declare(strict_types = 1);

namespace Web\CommandBus;

use Innmind\CommandBus\CommandBusInterface;
use Innmind\Neo4j\ONM\Manager;

final class PersisterBus implements CommandBusInterface
{
    private $commandBus;
    private $manager;

    public function __construct(
        CommandBusInterface $commandBus,
        Manager $manager
    ) {
        $this->commandBus = $commandBus;
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($command)
    {
        $this->commandBus->handle($command);
        $this->manager->flush();
    }
}
