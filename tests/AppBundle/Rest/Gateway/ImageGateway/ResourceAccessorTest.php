<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Rest\Gateway\ImageGateway;

use AppBundle\{
    Rest\Gateway\ImageGateway\ResourceAccessor,
    Entity\Image\Identity
};
use Domain\{
    Repository\ImageRepositoryInterface,
    Entity\Image,
    Entity\Image\Dimension,
    Entity\Image\Weight,
    Entity\Image\Description
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
    SetInterface,
    MapInterface
};
use Ramsey\Uuid\Uuid;

class ResourceAccessorTest extends \PHPUnit_Framework_TestCase
{
    private $accessor;
    private $repository;

    public function setUp()
    {
        $this->accessor = new ResourceAccessor(
            $this->repository = $this->createMock(ImageRepositoryInterface::class)
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
                $image = new Image(
                    new Identity($uuid),
                    new Path('foo'),
                    new Query('bar')
                )
            );
        $image->specifyDimension(new Dimension(42, 24));
        $image->specifyWeight(new Weight(1337));
        $image->addDescription(new Description('whatever'));
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
                    'dimension',
                    new Property(
                        'dimension',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'weight',
                    new Property(
                        'weight',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'descriptions',
                    new Property(
                        'descriptions',
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
        $this->assertSame('foo', $resource->property('path')->value());
        $this->assertSame('bar', $resource->property('query')->value());
        $this->assertInstanceOf(
            MapInterface::class,
            $resource->property('dimension')->value()
        );
        $this->assertSame(
            'string',
            (string) $resource->property('dimension')->value()->keyType()
        );
        $this->assertSame(
            'int',
            (string) $resource->property('dimension')->value()->valueType()
        );
        $this->assertSame(
            ['width', 'height'],
            $resource->property('dimension')->value()->keys()->toPrimitive()
        );
        $this->assertSame(
            [42, 24],
            $resource->property('dimension')->value()->values()->toPrimitive()
        );
        $this->assertSame(1337, $resource->property('weight')->value());
        $this->assertInstanceOf(
            SetInterface::class,
            $resource->property('descriptions')->value()
        );
        $this->assertSame(
            'string',
            (string) $resource->property('descriptions')->value()->type()
        );
        $this->assertSame(
            ['whatever'],
            $resource->property('descriptions')->value()->toPrimitive()
        );
    }
}