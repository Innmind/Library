<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway\HtmlPageGateway;

use Web\Gateway\HtmlPageGateway\ResourceLinker;
use Domain\{
    Command\RegisterAlternateResource,
    Command\MakeCanonicalLink,
    Entity\Alternate,
    Entity\Canonical,
    Entity\Alternate\Identity as AlternateIdentity,
    Entity\Canonical\Identity as CanonicalIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Model\Language,
    Exception\AlternateAlreadyExist,
    Exception\CanonicalAlreadyExist,
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
use Innmind\TimeContinuum\PointInTimeInterface;
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
            ->expects($this->exactly(3))
            ->method('__toString')
            ->willReturn((string) Uuid::uuid4());
        $to = $this->createMock(IdentityInterface::class);
        $to
            ->expects($this->exactly(4))
            ->method('__toString')
            ->willReturn((string) Uuid::uuid4());
        $bus
            ->expects($this->at(0))
            ->method('__invoke')
            ->with($this->callback(function($command) use ($identity, $to): bool {
                return $command instanceof RegisterAlternateResource &&
                    (string) $command->resource() === (string) $identity &&
                    (string) $command->alternate() === (string) $to &&
                    (string) $command->language() === 'fr-CA';
            }));
        $bus
            ->expects($this->at(1))
            ->method('__invoke')
            ->with($this->callback(function($command) use ($identity, $to): bool {
                return $command instanceof MakeCanonicalLink &&
                    (string) $command->resource() === (string) $identity &&
                    (string) $command->canonical() === (string) $to;
            }));

        $this->assertNull(
            $linker(
                new Reference($def, $identity),
                new Link(
                    new Reference($def, $to),
                    'alternate',
                    new Parameter\Parameter('language', 'fr-CA')
                ),
                new Link(
                    new Reference($def, $to),
                    'canonical'
                )
            )
        );
    }

    public function testExecuteEventWhenAlternateAlreadyExist()
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
                    new AlternateAlreadyExist(
                        new Alternate(
                            $this->createMock(AlternateIdentity::class),
                            $this->createMock(ResourceIdentity::class),
                            $this->createMock(ResourceIdentity::class),
                            new Language('fr')
                        )
                    )
                )
            );

        $this->assertNull(
            $linker(
                new Reference($def, $identity),
                new Link(
                    new Reference($def, $to),
                    'alternate',
                    new Parameter\Parameter('language', 'fr')
                )
            )
        );
    }

    public function testExecuteEventWhenCanonicalAlreadyExist()
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
                    new CanonicalAlreadyExist(
                        new Canonical(
                            $this->createMock(CanonicalIdentity::class),
                            $this->createMock(ResourceIdentity::class),
                            $this->createMock(ResourceIdentity::class),
                            $this->createMock(PointInTimeInterface::class)
                        )
                    )
                )
            );

        $this->assertNull(
            $linker(
                new Reference($def, $identity),
                new Link(
                    new Reference($def, $to),
                    'canonical'
                )
            )
        );
    }
}
