<?php
declare(strict_types = 1);

namespace Web\Gateway\HtmlPageGateway;

use App\Entity\HtmlPage\Identity;
use Domain\{
    Repository\HtmlPageRepository,
    Model\Language,
    Entity\HtmlPage\Anchor
};
use Innmind\Rest\Server\{
    ResourceAccessor as ResourceAccessorInterface,
    Identity as RestIdentity,
    HttpResource,
    HttpResource\Property,
    Definition\HttpResource as ResourceDefinition
};
use Innmind\Neo4j\DBAL\{
    Connection,
    Query\Query,
    Result\Row
};
use Innmind\Immutable\{
    Map,
    Set
};

final class ResourceAccessor implements ResourceAccessorInterface
{
    private HtmlPageRepository $repository;
    private Connection $dbal;

    public function __construct(
        HtmlPageRepository $repository,
        Connection $dbal
    ) {
        $this->repository = $repository;
        $this->dbal = $dbal;
    }

    public function __invoke(
        ResourceDefinition $definition,
        RestIdentity $identity
    ): HttpResource {
        $resource = $this->repository->get(
            new Identity($identity->toString())
        );
        $result = $this->dbal->execute(
            (new Query)
                ->match('host', 'Web', 'Host')
                ->linkedTo('resource', 'Web', 'Resource')
                ->through('RESOURCE_OF_HOST')
                ->where('resource.identity = {identity}')
                ->withParameter('identity', $identity->toString())
                ->with('host', 'resource')
                ->maybeMatch('author', 'Person', 'Author')
                ->linkedTo('resource')
                ->through('AUTHOR_OF_RESOURCE')
                ->with('host', 'resource', 'author')
                ->maybeMatch('citation', 'Citation')
                ->linkedTo('resource')
                ->through('CITED_IN_RESOURCE')
                ->return('host', 'author', 'collect(citation.text) as citations')
        );
        $properties = Map::of('string', Property::class)
            (
                'identity',
                new Property('identity', $resource->identity()->toString())
            )
            (
                'host',
                new Property('host', $result->rows()->first()->value()['name'])
            )
            (
                'path',
                new Property('path', $resource->path()->toString())
            )
            (
                'query',
                new Property('query', $resource->query()->toString())
            )
            (
                'languages',
                new Property(
                    'languages',
                    $resource
                        ->languages()
                        ->reduce(
                            Set::of('string'),
                            function(Set $carry, Language $language): Set {
                                return $carry->add((string) $language);
                            }
                        )
                )
            )
            (
                'anchors',
                new Property(
                    'anchors',
                    $resource
                        ->anchors()
                        ->reduce(
                            Set::of('string'),
                            function(Set $carry, Anchor $anchor): Set {
                                return $carry->add((string) $anchor);
                            }
                        )
                )
            )
            (
                'main_content',
                new Property('main_content', $resource->mainContent())
            )
            (
                'description',
                new Property('description', $resource->description())
            )
            (
                'title',
                new Property('title', $resource->title())
            );

        $authors = $result
            ->rows()
            ->filter(function(Row $row): bool {
                return $row->column() === 'author';
            });
        $citations = $result
            ->rows()
            ->filter(function(Row $row): bool {
                return $row->column() === 'citations';
            });

        if ($authors->count() > 0) {
            $properties = $properties->put(
                'author',
                new Property('author', $authors->first()->value()['name'])
            );
        }

        if ($citations->count() > 0) {
            $set = Set::of('string');

            foreach ($citations->first()->value() as $citation) {
                $set = $set->add($citation);
            }

            $properties = $properties->put(
                'citations',
                new Property('citations', $set)
            );
        }

        if ($resource->hasCharset()) {
            $properties = $properties->put(
                'charset',
                new Property('charset', (string) $resource->charset())
            );
        }

        if ($resource->hasThemeColour()) {
            $properties = $properties->put(
                'theme_colour',
                new Property('theme_colour', $resource->themeColour()->toString())
            );
        }

        if ($resource->hasAndroidAppLink()) {
            $properties = $properties->put(
                'android_app_link',
                new Property('android_app_link', $resource->androidAppLink()->toString())
            );
        }

        if ($resource->hasIosAppLink()) {
            $properties = $properties->put(
                'ios_app_link',
                new Property('ios_app_link', $resource->iosAppLink()->toString())
            );
        }

        if ($resource->hasPreview()) {
            $properties = $properties->put(
                'preview',
                new Property('preview', $resource->preview()->toString())
            );
        }

        return new HttpResource\HttpResource($definition, $properties);
    }
}
