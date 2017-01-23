<?php
declare(strict_types = 1);

namespace Tests\AppBundle\CommandBus;

use AppBundle\CommandBus\PersisterBus;
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Neo4j\{
    ONM\ManagerInterface,
    ONM\RepositoryInterface,
    ONM\IdentityInterface,
    DBAL\ConnectionInterface
};

class PersisterBusTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            CommandBusInterface::class,
            new PersisterBus(
                $this->createMock(CommandBusInterface::class),
                $this->createMock(ManagerInterface::class)
            )
        );
    }

    public function testHandle()
    {
        $command = new \stdClass;
        $handled = false;
        $innerBus = $this->createMock(CommandBusInterface::class);
        $innerBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback(function($innerCommand) use ($command, &$handled): bool {
                $handled = true;

                return $innerCommand === $command;
            }));
        $manager = new class($handled) implements ManagerInterface {
            private $handled;
            public $persisted = false;

            public function __construct(&$handled)
            {
                $this->handled = &$handled;
            }

            public function connection(): ConnectionInterface
            {
            }

            public function repository(string $class): RepositoryInterface
            {
            }

            public function flush(): ManagerInterface
            {
                if ($this->handled) {
                    $this->persisted = true;
                }

                return $this;
            }

            public function new(string $class): IdentityInterface
            {
            }
        };
        $bus = new PersisterBus($innerBus, $manager);

        $this->assertNull($bus->handle($command));
        $this->assertTrue($manager->persisted);
    }
}
