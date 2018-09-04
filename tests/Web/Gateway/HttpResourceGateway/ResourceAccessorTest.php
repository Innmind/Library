<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway\HttpResourceGateway;

use Web\{
    Gateway\HttpResourceGateway\ResourceAccessor,
    Entity\HttpResource\Identity
};
use Domain\{
    Repository\HttpResourceRepository,
    Entity\HttpResource as Entity,
    Entity\HttpResource\Charset,
    Model\Language
};
use Innmind\Url\{
    Path,
    Query
};
use Innmind\Rest\Server\{
    ResourceAccessor as ResourceAccessorInterface,
    Identity\Identity as RestIdentity,
    HttpResource\HttpResource,
    Definition\HttpResource as Definition,
    Definition\Identity as IdentityDefinition,
    Definition\Gateway,
    Definition\Property,
    Definition\Type,
    Definition\Access
};
use Innmind\Neo4j\DBAL\{
    Connection,
    Query as DBALQuery,
    Result,
    Result\Row
};
use Innmind\Immutable\{
    Map,
    Set,
    SetInterface,
    Stream
};
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class ResourceAccessorTest extends TestCase
{
    private $accessor;
    private $repository;
    private $dbal;

    public function setUp()
    {
        $this->accessor = new ResourceAccessor(
            $this->repository = $this->createMock(HttpResourceRepository::class),
            $this->dbal = $this->createMock(Connection::class)
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
        $this
            ->dbal
            ->expects($this->once())
            ->method('execute')
            ->with($this->callback(function(DBALQuery $query) use ($uuid): bool {
                return $query->cypher() === 'MATCH (host:Web:Host)-[:RESOURCE_OF_HOST]-(resource:Web:Resource) WHERE resource.identity = {identity} RETURN host' &&
                    $query->parameters()->count() === 1 &&
                    $query->parameters()->current()->key() === 'identity' &&
                    $query->parameters()->current()->value() === $uuid;
            }))
            ->willReturn(
                $result = $this->createMock(Result::class)
            );
        $result
            ->expects($this->once())
            ->method('rows')
            ->willReturn(
                (new Stream(Row::class))
                    ->add($row = $this->createMock(Row::class))
            );
        $row
            ->expects($this->once())
            ->method('value')
            ->willReturn(['name' => 'sub.example.com']);
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
                        $this->createMock(Type::class),
                        new Access,
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'host',
                    new Property(
                        'host',
                        $this->createMock(Type::class),
                        new Access,
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'path',
                    new Property(
                        'path',
                        $this->createMock(Type::class),
                        new Access,
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'query',
                    new Property(
                        'query',
                        $this->createMock(Type::class),
                        new Access,
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'languages',
                    new Property(
                        'languages',
                        $this->createMock(Type::class),
                        new Access,
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'charset',
                    new Property(
                        'charset',
                        $this->createMock(Type::class),
                        new Access,
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
        $this->assertSame('sub.example.com', $resource->property('host')->value());
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
