<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway\HttpResourceGateway;

use Web\Gateway\HttpResourceGateway\ResourceAccessor;
use App\Entity\HttpResource\Identity;
use Domain\{
    Repository\HttpResourceRepository,
    Entity\HttpResource as Entity,
    Entity\HttpResource\Charset,
    Model\Language,
};
use Innmind\Url\{
    Path,
    Query,
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
    Definition\Access,
};
use Innmind\Neo4j\DBAL\{
    Connection,
    Query as DBALQuery,
    Result,
    Result\Row,
};
use Innmind\Immutable\{
    Map,
    Set,
    Sequence,
};
use function Innmind\Immutable\unwrap;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class ResourceAccessorTest extends TestCase
{
    private $accessor;
    private $repository;
    private $dbal;

    public function setUp(): void
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
                return $identity->toString() === $uuid;
            }))
            ->willReturn(
                $resource = new Entity(
                    new Identity($uuid),
                    Path::of('foo'),
                    Query::of('bar')
                )
            );
        $this
            ->dbal
            ->expects($this->once())
            ->method('execute')
            ->with($this->callback(function(DBALQuery $query) use ($uuid): bool {
                return $query->cypher() === 'MATCH (host:Web:Host)-[:RESOURCE_OF_HOST]-(resource:Web:Resource) WHERE resource.identity = {identity} RETURN host' &&
                    $query->parameters()->count() === 1 &&
                    $query->parameters()->values()->first()->key() === 'identity' &&
                    $query->parameters()->values()->first()->value() === $uuid;
            }))
            ->willReturn(
                $result = $this->createMock(Result::class)
            );
        $result
            ->expects($this->once())
            ->method('rows')
            ->willReturn(
                Sequence::of(Row::class, $row = $this->createMock(Row::class))
            );
        $row
            ->expects($this->once())
            ->method('value')
            ->willReturn(['name' => 'sub.example.com']);
        $resource->specifyLanguages(
            Set::of(Language::class, new Language('fr'))
        );
        $resource->specifyCharset(new Charset('UTF-8'));
        $definition = new Definition(
            'http_resource',
            new Gateway('http_resource'),
            new IdentityDefinition('identity'),
            Set::of(
                Property::class,
                Property::required(
                    'identity',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                ),
                Property::required(
                    'host',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                ),
                Property::required(
                    'path',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                ),
                Property::required(
                    'query',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                ),
                Property::required(
                    'languages',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                ),
                Property::required(
                    'charset',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                )
            )
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
            Set::class,
            $resource->property('languages')->value()
        );
        $this->assertSame(
            'string',
            (string) $resource->property('languages')->value()->type()
        );
        $this->assertSame(
            ['fr'],
            unwrap($resource->property('languages')->value())
        );
        $this->assertSame('UTF-8', $resource->property('charset')->value());
    }
}
