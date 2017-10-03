<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\HtmlPageGateway;

use AppBundle\Entity\HtmlPage\Identity;
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
    private $repository;
    private $dbal;

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
            new Identity((string) $identity)
        );
        $result = $this->dbal->execute(
            (new Query)
                ->match('host', ['Web', 'Host'])
                ->linkedTo('resource', ['Web', 'Resource'])
                ->through('RESOURCE_OF_HOST')
                ->where('resource.identity = {identity}')
                ->withParameter('identity', (string) $identity)
                ->with('host', 'resource')
                ->maybeMatch('author', ['Person', 'Author'])
                ->linkedTo('resource')
                ->through('AUTHOR_OF_RESOURCE')
                ->with('host', 'resource', 'author')
                ->maybeMatch('citation', ['Citation'])
                ->linkedTo('resource')
                ->through('CITED_IN_RESOURCE')
                ->return('host', 'author', 'collect(citation.text) as citations')
        );
        $properties = (new Map('string', Property::class))
            ->put(
                'identity',
                new Property('identity', (string) $resource->identity())
            )
            ->put(
                'host',
                new Property('host', $result->rows()->first()->value()['name'])
            )
            ->put(
                'path',
                new Property('path', (string) $resource->path())
            )
            ->put(
                'query',
                new Property('query', (string) $resource->query())
            )
            ->put(
                'languages',
                new Property(
                    'languages',
                    $resource
                        ->languages()
                        ->reduce(
                            new Set('string'),
                            function(Set $carry, Language $language): Set {
                                return $carry->add((string) $language);
                            }
                        )
                )
            )
            ->put(
                'anchors',
                new Property(
                    'anchors',
                    $resource
                        ->anchors()
                        ->reduce(
                            new Set('string'),
                            function(Set $carry, Anchor $anchor): Set {
                                return $carry->add((string) $anchor);
                            }
                        )
                )
            )
            ->put(
                'main_content',
                new Property('main_content', $resource->mainContent())
            )
            ->put(
                'description',
                new Property('description', $resource->description())
            )
            ->put(
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
            $set = new Set('string');

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
                new Property('theme_colour', (string) $resource->themeColour())
            );
        }

        if ($resource->hasAndroidAppLink()) {
            $properties = $properties->put(
                'android_app_link',
                new Property('android_app_link', (string) $resource->androidAppLink())
            );
        }

        if ($resource->hasIosAppLink()) {
            $properties = $properties->put(
                'ios_app_link',
                new Property('ios_app_link', (string) $resource->iosAppLink())
            );
        }

        if ($resource->hasPreview()) {
            $properties = $properties->put(
                'preview',
                new Property('preview', (string) $resource->preview())
            );
        }

        return new HttpResource\HttpResource($definition, $properties);
    }
}
