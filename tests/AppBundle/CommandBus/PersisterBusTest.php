<?php
declare(strict_types = 1);

namespace Tests\AppBundle\CommandBus;

use AppBundle\CommandBus\PersisterBus;
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Neo4j\{
    ONM\Manager,
    ONM\Repository,
    ONM\Identity\Generators,
    DBAL\Connection
};
use PHPUnit\Framework\TestCase;

class PersisterBusTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            CommandBusInterface::class,
            new PersisterBus(
                $this->createMock(CommandBusInterface::class),
                $this->createMock(Manager::class)
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
        $manager = new class($handled) implements Manager {
            private $handled;
            public $persisted = false;

            public function __construct(&$handled)
            {
                $this->handled = &$handled;
            }

            public function connection(): Connection
            {
            }

            public function repository(string $class): Repository
            {
            }

            public function flush(): Manager
            {
                if ($this->handled) {
                    $this->persisted = true;
                }

                return $this;
            }

            public function identities(): Generators
            {
            }
        };
        $bus = new PersisterBus($innerBus, $manager);

        $this->assertNull($bus->handle($command));
        $this->assertTrue($manager->persisted);
    }
}
