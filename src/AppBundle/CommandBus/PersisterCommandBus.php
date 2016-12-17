<?php
declare(strict_types = 1);

namespace AppBundle\CommandBus;

use Innmind\CommandBus\CommandBusInterface;
use Innmind\Neo4j\ONM\ManagerInterface;

final class PersisterCommandBus implements CommandBusInterface
{
    private $commandBus;
    private $manager;

    public function __construct(
        CommandBusInterface $commandBus,
        ManagerInterface $manager
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
