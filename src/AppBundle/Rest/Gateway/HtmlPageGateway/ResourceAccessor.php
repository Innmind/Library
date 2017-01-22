<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\HtmlPageGateway;

use AppBundle\Entity\HtmlPage\Identity;
use Domain\{
    Repository\HtmlPageRepositoryInterface,
    Model\Language,
    Entity\HtmlPage\Anchor
};
use Innmind\Rest\Server\{
    ResourceAccessorInterface,
    IdentityInterface,
    HttpResourceInterface,
    HttpResource,
    Property,
    Definition\HttpResource as ResourceDefinition
};
use Innmind\Immutable\{
    Map,
    Set
};

final class ResourceAccessor implements ResourceAccessorInterface
{
    private $repository;

    public function __construct(HtmlPageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(
        ResourceDefinition $definition,
        IdentityInterface $identity
    ): HttpResourceInterface {
        $resource = $this->repository->get(
            new Identity((string) $identity)
        );
        $properties = (new Map('string', Property::class))
            ->put(
                'identity',
                new Property('identity', (string) $resource->identity())
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

        return new HttpResource($definition, $properties);
    }
}
