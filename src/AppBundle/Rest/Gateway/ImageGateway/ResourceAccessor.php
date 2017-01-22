<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\ImageGateway;

use AppBundle\Entity\Image\Identity;
use Domain\{
    Repository\ImageRepositoryInterface,
    Entity\Image\Description
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

    public function __construct(ImageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(
        ResourceDefinition $definition,
        IdentityInterface $identity
    ): HttpResourceInterface {
        $image = $this->repository->get(
            new Identity((string) $identity)
        );
        $properties = (new Map('string', Property::class))
            ->put(
                'identity',
                new Property('identity', (string) $image->identity())
            )
            ->put(
                'path',
                new Property('path', (string) $image->path())
            )
            ->put(
                'query',
                new Property('query', (string) $image->query())
            )
            ->put(
                'descriptions',
                new Property(
                    'descriptions',
                    $image
                        ->descriptions()
                        ->reduce(
                            new Set('string'),
                            function(Set $carry, Description $description): Set {
                                return $carry->add((string) $description);
                            }
                        )
                )
            );

        if ($image->isDimensionKnown()) {
            $properties = $properties->put(
                'dimension',
                new Property(
                    'dimension',
                    (new Map('string', 'int'))
                        ->put('width', $image->dimension()->width())
                        ->put('height', $image->dimension()->height())
                )
            );
        }

        if ($image->isWeightKnown()) {
            $properties = $properties->put(
                'weight',
                new Property('weight', $image->weight()->toInt())
            );
        }

        return new HttpResource($definition, $properties);
    }
}
