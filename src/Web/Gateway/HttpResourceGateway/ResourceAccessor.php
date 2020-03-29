<?php
declare(strict_types = 1);

namespace Web\Gateway\HttpResourceGateway;

use App\Entity\HttpResource\Identity;
use Domain\{
    Repository\HttpResourceRepository,
    Model\Language,
};
use Innmind\Rest\Server\{
    ResourceAccessor as ResourceAccessorInterface,
    Identity as RestIdentity,
    HttpResource,
    HttpResource\Property,
    Definition\HttpResource as ResourceDefinition,
};
use Innmind\Neo4j\DBAL\{
    Connection,
    Query\Query,
};
use Innmind\Immutable\{
    Map,
    Set,
};

final class ResourceAccessor implements ResourceAccessorInterface
{
    private HttpResourceRepository $repository;
    private Connection $dbal;

    public function __construct(
        HttpResourceRepository $repository,
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
                ->return('host')
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
