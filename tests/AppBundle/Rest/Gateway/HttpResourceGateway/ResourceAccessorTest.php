<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Rest\Gateway\HttpResourceGateway;

use AppBundle\{
    Rest\Gateway\HttpResourceGateway\ResourceAccessor,
    Entity\HttpResource\Identity
};
use Domain\{
    Repository\HttpResourceRepositoryInterface,
    Entity\HttpResource as Entity,
    Entity\HttpResource\Charset,
    Model\Language
};
use Innmind\Url\{
    Path,
    Query
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
    Set,
    SetInterface
};
use Ramsey\Uuid\Uuid;

class ResourceAccessorTest extends \PHPUnit_Framework_TestCase
{
    private $accessor;
    private $repository;

    public function setUp()
    {
        $this->accessor = new ResourceAccessor(
            $this->repository = $this->createMock(HttpResourceRepositoryInterface::class)
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
                $resource = new Entity(
                    new Identity($uuid),
                    new Path('foo'),
                    new Query('bar')
                )
            );
        $resource->specifyLanguages(
            (new Set(Language::class))->add(new Language('fr'))
        );
        $resource->specifyCharset(new Charset('UTF-8'));
        $definition = new Definition(
            'http_resource',
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
                    'path',
                    new Property(
                        'path',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'query',
                    new Property(
                        'query',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'languages',
                    new Property(
                        'languages',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'charset',
                    new Property(
                        'charset',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                ),
            new Map('scalar', 'variable'),
            new Map('scalar', 'variable'),
            new Gateway('http_resource'),
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
        $this->assertSame('foo', $resource->property('path')->value());
        $this->assertSame('bar', $resource->property('query')->value());
        $this->assertInstanceOf(
            SetInterface::class,
            $resource->property('languages')->value()
        );
        $this->assertSame(
            'string',
            (string) $resource->property('languages')->value()->type()
        );
        $this->assertSame(
            ['fr'],
            $resource->property('languages')->value()->toPrimitive()
        );
        $this->assertSame('UTF-8', $resource->property('charset')->value());
    }
}
