<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway\HttpResourceGateway;

use Web\Gateway\HttpResourceGateway\ResourceLinker;
use Domain\{
    Command\ReferResource,
    Entity\Reference as Entity,
    Entity\Reference\Identity as ReferenceIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Exception\ReferenceAlreadyExist,
};
use Innmind\Rest\Server\{
    ResourceLinker as ResourceLinkerInterface,
    Definition\HttpResource,
    Definition\Identity,
    Definition\Gateway,
    Definition\Property,
    Identity as IdentityInterface,
    Reference,
    Link,
    Link\Parameter,
};
use Innmind\CommandBus\CommandBus;
use Innmind\Immutable\{
    MapInterface,
    Map,
    Set,
};
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class ResourceLinkerTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            ResourceLinkerInterface::class,
            new ResourceLinker(
                $this->createMock(CommandBus::class)
            )
        );
    }

    public function testExecution()
    {
        $linker = new ResourceLinker(
            $bus = $this->createMock(CommandBus::class)
        );
        $def = HttpResource::rangeable(
            'foo',
            new Gateway('foo'),
            new Identity('foo'),
            Set::of(Property::class)
        );
        $identity = $this->createMock(IdentityInterface::class);
        $identity
            ->expects($this->exactly(2))
            ->method('__toString')
            ->willReturn((string) Uuid::uuid4());
        $to = $this->createMock(IdentityInterface::class);
        $to
            ->expects($this->exactly(2))
            ->method('__toString')
            ->willReturn((string) Uuid::uuid4());
        $bus
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(function($command) use ($identity, $to): bool {
                return $command instanceof ReferResource &&
                    (string) $command->source() === (string) $identity &&
                    (string) $command->target() === (string) $to;
            }));

        $this->assertNull(
            $linker(
                new Reference($def, $identity),
                new Link(
                    new Reference($def, $to),
                    'referrer'
                )
            )
        );
    }

    public function testExecuteEventWhenReferenceAlreadyExist()
    {
        $linker = new ResourceLinker(
            $bus = $this->createMock(CommandBus::class)
        );
        $def = HttpResource::rangeable(
            'foo',
            new Gateway('foo'),
            new Identity('foo'),
            Set::of(Property::class)
        );
        $identity = $this->createMock(IdentityInterface::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn((string) Uuid::uuid4());
        $to = $this->createMock(IdentityInterface::class);
        $to
            ->expects($this->once())
            ->method('__toString')
            ->willReturn((string) Uuid::uuid4());
        $bus
            ->expects($this->once())
            ->method('__invoke')
            ->will(
                $this->throwException(
                    new ReferenceAlreadyExist(
                        new Entity(
                            $this->createMock(ReferenceIdentity::class),
                            $this->createMock(ResourceIdentity::class),
                            $this->createMock(ResourceIdentity::class)
                        )
                    )
                )
            );

        $this->assertNull(
            $linker(
                new Reference($def, $identity),
                new Link(
                    new Reference($def, $to),
                    'referrer'
                )
            )
        );
    }
}
