<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway\HtmlPageGateway;

use Web\Gateway\HtmlPageGateway\ResourceAccessor;
use App\Entity\HtmlPage\Identity;
use Domain\{
    Repository\HtmlPageRepository,
    Entity\HtmlPage,
    Entity\HtmlPage\Anchor,
    Entity\HttpResource\Charset,
    Model\Language,
};
use Innmind\Url\{
    Path,
    Query,
    Url,
};
use Innmind\Colour\RGBA;
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
            $this->repository = $this->createMock(HtmlPageRepository::class),
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
                $page = new HtmlPage(
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
                return $query->cypher() === 'MATCH (host:Web:Host)-[:RESOURCE_OF_HOST]-(resource:Web:Resource) WHERE resource.identity = {identity} WITH host, resource OPTIONAL MATCH (author:Person:Author)-[:AUTHOR_OF_RESOURCE]-(resource) WITH host, resource, author OPTIONAL MATCH (citation:Citation)-[:CITED_IN_RESOURCE]-(resource) RETURN host, author, collect(citation.text) as citations' &&
                    $query->parameters()->count() === 1 &&
                    $query->parameters()->values()->first()->key() === 'identity' &&
                    $query->parameters()->values()->first()->value() === $uuid;
            }))
            ->willReturn(
                $result = $this->createMock(Result::class)
            );
        $result
            ->expects($this->exactly(3))
            ->method('rows')
            ->willReturn(
                Sequence::of(Row::class, $row = $this->createMock(Row::class))
            );
        $row
            ->expects($this->once())
            ->method('value')
            ->willReturn(['name' => 'sub.example.com']);
        $page->specifyLanguages(
            Set::of(Language::class, new Language('fr'))
        );
        $page->specifyCharset(new Charset('UTF-8'));
        $page->flagAsJournal();
        $page->specifyAnchors(
            Set::of(Anchor::class, new Anchor('someAnchor'))
        );
        $page->specifyAndroidAppLink(Url::of('android://foo/'));
        $page->specifyIosAppLink(Url::of('ios://foo/'));
        $page->specifyDescription('desc');
        $page->specifyMainContent('content');
        $page->specifyThemeColour(RGBA::of('39f'));
        $page->specifyTitle('title');
        $page->usePreview(Url::of('http://some.photo/url'));
        $definition = new Definition(
            'html_page',
            new Gateway('html_page'),
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
                ),
                Property::required(
                    'main_content',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                ),
                Property::required(
                    'description',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                ),
                Property::required(
                    'anchors',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                ),
                Property::required(
                    'theme_colour',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                ),
                Property::required(
                    'title',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                ),
                Property::required(
                    'android_app_link',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                ),
                Property::required(
                    'ios_app_link',
                    $this->createMock(Type::class),
                    new Access(Access::CREATE)
                ),
                Property::required(
                    'preview',
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
        $this->assertSame('content', $resource->property('main_content')->value());
        $this->assertSame('desc', $resource->property('description')->value());
        $this->assertSame(
            'string',
            (string) $resource->property('anchors')->value()->type()
        );
        $this->assertSame(
            ['#someAnchor'],
            unwrap($resource->property('anchors')->value())
        );
        $this->assertSame('#3399ff', $resource->property('theme_colour')->value());
        $this->assertSame('title', $resource->property('title')->value());
        $this->assertSame('android://foo/', $resource->property('android_app_link')->value());
        $this->assertSame('ios://foo/', $resource->property('ios_app_link')->value());
        $this->assertSame('http://some.photo/url', $resource->property('preview')->value());
    }
}
