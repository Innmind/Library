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
    SetInterface,
    Stream,
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
                return (string) $identity === $uuid;
            }))
            ->willReturn(
                $page = new HtmlPage(
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
                return $query->cypher() === 'MATCH (host:Web:Host)-[:RESOURCE_OF_HOST]-(resource:Web:Resource) WHERE resource.identity = {identity} WITH host, resource OPTIONAL MATCH (author:Person:Author)-[:AUTHOR_OF_RESOURCE]-(resource) WITH host, resource, author OPTIONAL MATCH (citation:Citation)-[:CITED_IN_RESOURCE]-(resource) RETURN host, author, collect(citation.text) as citations' &&
                    $query->parameters()->count() === 1 &&
                    $query->parameters()->current()->key() === 'identity' &&
                    $query->parameters()->current()->value() === $uuid;
            }))
            ->willReturn(
                $result = $this->createMock(Result::class)
            );
        $result
            ->expects($this->exactly(3))
            ->method('rows')
            ->willReturn(
                (new Stream(Row::class))
                    ->add($row = $this->createMock(Row::class))
            );
        $row
            ->expects($this->once())
            ->method('value')
            ->willReturn(['name' => 'sub.example.com']);
        $page->specifyLanguages(
            (new Set(Language::class))->add(new Language('fr'))
        );
        $page->specifyCharset(new Charset('UTF-8'));
        $page->flagAsJournal();
        $page->specifyAnchors(
            (new Set(Anchor::class))->add(new Anchor('someAnchor'))
        );
        $page->specifyAndroidAppLink(Url::fromString('android://foo/'));
        $page->specifyIosAppLink(Url::fromString('ios://foo/'));
        $page->specifyDescription('desc');
        $page->specifyMainContent('content');
        $page->specifyThemeColour(RGBA::fromString('39f'));
        $page->specifyTitle('title');
        $page->usePreview(Url::fromString('http://some.photo/url'));
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
        $this->assertSame('content', $resource->property('main_content')->value());
        $this->assertSame('desc', $resource->property('description')->value());
        $this->assertSame(
            'string',
            (string) $resource->property('anchors')->value()->type()
        );
        $this->assertSame(
            ['#someAnchor'],
            $resource->property('anchors')->value()->toPrimitive()
        );
        $this->assertSame('#3399ff', $resource->property('theme_colour')->value());
        $this->assertSame('title', $resource->property('title')->value());
        $this->assertSame('android://foo/', $resource->property('android_app_link')->value());
        $this->assertSame('ios://foo/', $resource->property('ios_app_link')->value());
        $this->assertSame('http://some.photo/url', $resource->property('preview')->value());
    }
}
