<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Rest\Gateway\HtmlPageGateway;

use AppBundle\{
    Rest\Gateway\HtmlPageGateway\ResourceAccessor,
    Entity\HtmlPage\Identity
};
use Domain\{
    Repository\HtmlPageRepositoryInterface,
    Entity\HtmlPage,
    Entity\HtmlPage\Anchor,
    Entity\HttpResource\Charset,
    Model\Language
};
use Innmind\Url\{
    Path,
    Query,
    Url
};
use Innmind\Colour\RGBA;
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
use Innmind\Neo4j\DBAL\{
    ConnectionInterface,
    QueryInterface,
    ResultInterface,
    Result\RowInterface
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
            $this->repository = $this->createMock(HtmlPageRepositoryInterface::class),
            $this->dbal = $this->createMock(ConnectionInterface::class)
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
            ->with($this->callback(function(QueryInterface $query) use ($uuid): bool {
                return $query->cypher() === 'MATCH (host:Web:Host)-[:RESOURCE_OF_HOST]-(resource:Web:Resource) WHERE resource.identity = {identity} WITH host, resource MATCH (author:Person:Author)-[:AUTHOR_OF_RESOURCE]-(resource) WITH host, resource, author MATCH (citation:Citation)-[:CITED_IN_RESOURCE]-(resource) RETURN host, author, collect(citation.text) as citations' &&
                    $query->parameters()->count() === 1 &&
                    $query->parameters()->current()->key() === 'identity' &&
                    $query->parameters()->current()->value() === $uuid;
            }))
            ->willReturn(
                $result = $this->createMock(ResultInterface::class)
            );
        $result
            ->expects($this->exactly(3))
            ->method('rows')
            ->willReturn(
                (new Stream(RowInterface::class))
                    ->add($row = $this->createMock(RowInterface::class))
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
                    'host',
                    new Property(
                        'host',
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
                )
                ->put(
                    'main_content',
                    new Property(
                        'main_content',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'description',
                    new Property(
                        'description',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'anchors',
                    new Property(
                        'anchors',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'theme_colour',
                    new Property(
                        'theme_colour',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'title',
                    new Property(
                        'title',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'android_app_link',
                    new Property(
                        'android_app_link',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'ios_app_link',
                    new Property(
                        'ios_app_link',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                )
                ->put(
                    'preview',
                    new Property(
                        'preview',
                        $this->createMock(TypeInterface::class),
                        new Access(new Set('string')),
                        new Set('string'),
                        false
                    )
                ),
            new Map('scalar', 'variable'),
            new Map('scalar', 'variable'),
            new Gateway('html_page'),
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
