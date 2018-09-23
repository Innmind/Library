<?php
declare(strict_types = 1);

namespace Web\Gateway\ImageGateway;

use App\Entity\Image\Identity;
use Domain\{
    Repository\ImageRepository,
    Entity\Image\Description
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
    Query\Query
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
        ImageRepository $repository,
        Connection $dbal
    ) {
        $this->repository = $repository;
        $this->dbal = $dbal;
    }

    public function __invoke(
        ResourceDefinition $definition,
        RestIdentity $identity
    ): HttpResource {
        $image = $this->repository->get(
            new Identity((string) $identity)
        );
        $result = $this->dbal->execute(
            (new Query)
                ->match('host', ['Web', 'Host'])
                ->linkedTo('resource', ['Web', 'Resource'])
                ->through('RESOURCE_OF_HOST')
                ->where('resource.identity = {identity}')
                ->withParameter('identity', (string) $identity)
                ->return('host')
        );
        $properties = (new Map('string', Property::class))
            ->put(
                'identity',
                new Property('identity', (string) $image->identity())
            )
            ->put(
                'host',
                new Property('host', $result->rows()->first()->value()['name'])
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

        return new HttpResource\HttpResource($definition, $properties);
    }
}
