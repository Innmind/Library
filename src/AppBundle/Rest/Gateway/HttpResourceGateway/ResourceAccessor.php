<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\HttpResourceGateway;

use AppBundle\Entity\HttpResource\Identity;
use Domain\{
    Repository\HttpResourceRepositoryInterface,
    Model\Language
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
        HttpResourceRepositoryInterface $repository,
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
                ->return('host')
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
            );

        if ($resource->hasCharset()) {
            $properties = $properties->put(
                'charset',
                new Property('charset', (string) $resource->charset())
            );
        }

        return new HttpResource\HttpResource($definition, $properties);
    }
}
