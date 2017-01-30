<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Rest\Gateway\HtmlPageGateway;

use AppBundle\Rest\Gateway\HtmlPageGateway\ResourceLinker;
use Domain\{
    Command\RegisterAlternateResource,
    Entity\Alternate,
    Entity\Alternate\IdentityInterface as AlternateIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Model\Language,
    Exception\AlternateAlreadyExistException
};
use Innmind\Rest\Server\{
    ResourceLinkerInterface,
    Definition\HttpResource,
    Definition\Identity,
    Definition\Gateway,
    Definition\Property,
    IdentityInterface,
    Reference,
    Link\ParameterInterface,
    Link\Parameter
};
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Immutable\{
    MapInterface,
    Map
};
use Ramsey\Uuid\Uuid;

class ResourceLinkerTest extends \PHPUnit_Framework_TestCase
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
     * @expectedException Innmind\Http\Exception\Http\BadRequestException
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
                    new Map('string', ParameterInterface::class)
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
                return $command instanceof RegisterAlternateResource &&
                    (string) $command->resource() === (string) $identity &&
                    (string) $command->alternate() === (string) $to &&
                    (string) $command->language() === 'fr-CA';
            }));

        $this->assertNull(
            $linker(
                new Reference($def, $identity),
                (new Map(Reference::class, MapInterface::class))
                    ->put(
                        new Reference($def, $to),
                        (new Map('string', ParameterInterface::class))
                            ->put(
                                'rel',
                                new Parameter('rel', 'alternate')
                            )
                            ->put(
                                'language',
                                new Parameter('language', 'fr-CA')
                            )
                    )
            )
        );
    }

    public function testExecuteEventWhenLinkAlreadyExist()
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
                    new AlternateAlreadyExistException(
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
                (new Map(Reference::class, MapInterface::class))
                    ->put(
                        new Reference($def, $to),
                        (new Map('string', ParameterInterface::class))
                            ->put(
                                'rel',
                                new Parameter('rel', 'alternate')
                            )
                            ->put(
                                'language',
                                new Parameter('language', 'fr')
                            )
                    )
            )
        );
    }
}
