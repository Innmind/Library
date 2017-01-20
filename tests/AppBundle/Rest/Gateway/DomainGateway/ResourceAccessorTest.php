<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Rest\Gateway\DomainGateway;

use AppBundle\{
    Rest\Gateway\DomainGateway\ResourceAccessor,
    Entity\Domain\Identity
};
use Domain\{
    Repository\DomainRepositoryInterface,
    Entity\Domain,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain
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
            $this->repository = $this->createMock(DomainRepositoryInterface::class)
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
                new Domain(
                    new Identity($uuid),
                    new Name('foo'),
                    new TopLevelDomain('co.uk')
                )
            );
        $definition = new Definition(
            'domain',
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
                    'name',
                    new Property(
                        'name',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'tld',
                    new Property(
                        'tld',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                ),
            new Map('scalar', 'variable'),
            new Map('scalar', 'variable'),
            new Gateway('domain'),
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
        $this->assertSame('foo', $resource->property('name')->value());
        $this->assertSame('co.uk', $resource->property('tld')->value());
    }
}
