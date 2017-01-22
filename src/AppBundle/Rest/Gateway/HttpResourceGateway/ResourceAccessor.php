<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\HttpResourceGateway;

use AppBundle\Entity\HttpResource\Identity;
use Domain\{
    Repository\HttpResourceRepositoryInterface,
    Model\Language
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

    public function __construct(HttpResourceRepositoryInterface $repository)
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
            );

        if ($resource->hasCharset()) {
            $properties = $properties->put(
                'charset',
                new Property('charset', (string) $resource->charset())
            );
        }

        return new HttpResource($definition, $properties);
    }
}
