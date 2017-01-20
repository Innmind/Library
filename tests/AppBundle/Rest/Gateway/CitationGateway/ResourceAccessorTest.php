<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Rest\Gateway\CitationGateway;

use AppBundle\{
    Rest\Gateway\CitationGateway\ResourceAccessor,
    Entity\Citation\Identity
};
use Domain\{
    Repository\CitationRepositoryInterface,
    Entity\Citation,
    Entity\Citation\Text
};
use Innmind\Rest\Server\{
    ResourceAccessorInterface,
    Identity as RestIdentity,
    HttpResource,
    Definition\HttpResource as Definition,
    Definition\Identity as IdentityDefinition,
    Definition\Gateway,
    Definition\Property,
    Definition\TypeInterface,
    Definition\Access
};
use Innmind\Immutable\{
    Map,
    Set
};
use Ramsey\Uuid\Uuid;

class ResourceAccessorTest extends \PHPUnit_Framework_TestCase
{
    private $accessor;
    private $repository;

    public function setUp()
    {
        $this->accessor = new ResourceAccessor(
            $this->repository = $this->createMock(CitationRepositoryInterface::class)
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            ResourceAccessorInterface::class,
            $this->accessor
        );
    }

    public function testExecution()
    {
        $uuid = (string) Uuid::uuid4();
        $this
            ->repository
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function(Identity $identity) use ($uuid) {
                return (string) $identity === $uuid;
            }))
            ->willReturn(
                new Citation(
                    new Identity($uuid),
                    new Text('foo')
                )
            );
        $definition = new Definition(
            'citation',
            new IdentityDefinition('identity'),
            (new Map('string', Property::class))
                ->put(
                    'identity',
                    new Property(
                        'identity',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'text',
                    new Property(
                        'text',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                ),
            new Map('scalar', 'variable'),
            new Map('scalar', 'variable'),
            new Gateway('citation'),
            false,
            new Map('string', 'string')
        );

        $resource = ($this->accessor)(
            $definition,
            new RestIdentity($uuid)
        );

        $this->assertInstanceOf(
            HttpResource::class,
            $resource
        );
        $this->assertSame($definition, $resource->definition());
        $this->assertSame($uuid, $resource->property('identity')->value());
        $this->assertSame('foo', $resource->property('text')->value());
    }
}
