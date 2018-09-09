<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway\HttpResourceGateway;

use Web\Gateway\HttpResourceGateway\ResourceLinker;
use Domain\{
    Command\ReferResource,
    Entity\Reference as Entity,
    Entity\Reference\Identity as ReferenceIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Exception\ReferenceAlreadyExist
};
use Innmind\Rest\Server\{
    ResourceLinker as ResourceLinkerInterface,
    Definition\HttpResource,
    Definition\Identity,
    Definition\Gateway,
    Definition\Property,
    Identity as IdentityInterface,
    Reference,
    Link\Parameter
};
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Immutable\{
    MapInterface,
    Map
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
                $this->createMock(CommandBusInterface::class)
            )
        );
    }

    /**
     * @expectedException Innmind\Http\Exception\Http\BadRequest
     */
    public function testThrowWhenInvalidDefinition()
    {
        $linker = new ResourceLinker(
            $this->createMock(CommandBusInterface::class)
        );
        $def1 = new HttpResource(
            'foo',
            new Identity('foo'),
            new Map('string', Property::class),
            new Map('scalar', 'variable'),
            new Map('scalar', 'variable'),
            new Gateway('foo'),
            true,
            new Map('string', 'string')
        );
        $def2 = clone $def1;
        $identity = $this->createMock(IdentityInterface::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn((string) Uuid::uuid4());

        $linker(
            new Reference($def1, $identity),
            (new Map(Reference::class, MapInterface::class))
                ->put(
                    new Reference(
                        $def2,
                        $this->createMock(IdentityInterface::class)
                    ),
                    new Map('string', Parameter::class)
                )
        );
    }

    public function testExecution()
    {
        $linker = new ResourceLinker(
            $bus = $this->createMock(CommandBusInterface::class)
        );
        $def = new HttpResource(
            'foo',
            new Identity('foo'),
            new Map('string', Property::class),
            new Map('scalar', 'variable'),
            new Map('scalar', 'variable'),
            new Gateway('foo'),
            true,
            new Map('string', 'string')
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
            ->method('handle')
            ->with($this->callback(function($command) use ($identity, $to): bool {
                return $command instanceof ReferResource &&
                    (string) $command->source() === (string) $identity &&
                    (string) $command->target() === (string) $to;
            }));

        $this->assertNull(
            $linker(
                new Reference($def, $identity),
                (new Map(Reference::class, MapInterface::class))
                    ->put(
                        new Reference($def, $to),
                        (new Map('string', Parameter::class))
                            ->put(
                                'rel',
                                new Parameter\Parameter('rel', 'referrer')
                            )
                    )
            )
        );
    }

    public function testExecuteEventWhenReferenceAlreadyExist()
    {
        $linker = new ResourceLinker(
            $bus = $this->createMock(CommandBusInterface::class)
        );
        $def = new HttpResource(
            'foo',
            new Identity('foo'),
            new Map('string', Property::class),
            new Map('scalar', 'variable'),
            new Map('scalar', 'variable'),
            new Gateway('foo'),
            true,
            new Map('string', 'string')
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
            ->method('handle')
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
                (new Map(Reference::class, MapInterface::class))
                    ->put(
                        new Reference($def, $to),
                        (new Map('string', Parameter::class))
                            ->put(
                                'rel',
                                new Parameter\Parameter('rel', 'referrer')
                            )
                    )
            )
        );
    }
}
