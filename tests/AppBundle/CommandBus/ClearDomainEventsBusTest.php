<?php
declare(strict_types = 1);

namespace Tests\AppBundle\CommandBus;

use AppBundle\CommandBus\ClearDomainEventsBus;
use Innmind\CommandBus\CommandBusInterface;
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};
use Innmind\Neo4j\ONM\{
    Entity\Container,
    IdentityInterface
};

class ClearDomainEventsBusTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            CommandBusInterface::class,
            new ClearDomainEventsBus(
                $this->createMock(CommandBusInterface::class),
                new Container
            )
        );
    }

    public function testHandle()
    {
        $command = new \stdClass;
        $commandBus = $this->createMock(CommandBusInterface::class);
        $commandBus
            ->expects($this->once())
            ->method('handle')
            ->with($command);
        $container = new Container;
        $container
            ->push(
                $this->createMock(IdentityInterface::class),
                new class {},
                Container::STATE_NEW
            )
            ->push(
                $this->createMock(IdentityInterface::class),
                $entity1 = new class implements ContainsRecordedEventsInterface {
                    use EventRecorder;

                    public function __construct()
                    {
                        $this->record(new \stdClass);
                    }
                },
                Container::STATE_NEW
            )
            ->push(
                $this->createMock(IdentityInterface::class),
                new class {},
                Container::STATE_MANAGED
            )
            ->push(
                $this->createMock(IdentityInterface::class),
                $entity2 = new class implements ContainsRecordedEventsInterface {
                    use EventRecorder;

                    public function __construct()
                    {
                        $this->record(new \stdClass);
                    }
                },
                Container::STATE_MANAGED
            )
            ->push(
                $this->createMock(IdentityInterface::class),
                new class {},
                Container::STATE_TO_BE_REMOVED
            )
            ->push(
                $this->createMock(IdentityInterface::class),
                $entity3 = new class implements ContainsRecordedEventsInterface {
                    use EventRecorder;

                    public function __construct()
                    {
                        $this->record(new \stdClass);
                    }
                },
                Container::STATE_TO_BE_REMOVED
            )
            ->push(
                $this->createMock(IdentityInterface::class),
                new class {},
                Container::STATE_REMOVED
            )
            ->push(
                $this->createMock(IdentityInterface::class),
                $entity4 = new class implements ContainsRecordedEventsInterface {
                    use EventRecorder;

                    public function __construct()
                    {
                        $this->record(new \stdClass);
                    }
                },
                Container::STATE_REMOVED
            );
        $bus = new ClearDomainEventsBus($commandBus, $container);

        $this->assertNull($bus->handle($command));
        $this->assertCount(0, $entity1->recordedEvents());
        $this->assertCount(0, $entity2->recordedEvents());
        $this->assertCount(0, $entity3->recordedEvents());
        $this->assertCount(0, $entity4->recordedEvents());
    }
}
