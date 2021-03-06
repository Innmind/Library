<?php
declare(strict_types = 1);

namespace Web\Gateway\ImageGateway;

use App\Entity\Image\Identity;
use Domain\{
    Repository\ImageRepository,
    Entity\Image\Description,
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
    private ImageRepository $repository;
    private Connection $dbal;

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
        /**
         * @psalm-suppress PossiblyInvalidArrayAccess
         * @var list<Property>
         */
        $properties = [
            new Property('identity', $image->identity()->toString()),
            new Property('host', $result->rows()->first()->value()['name']),
            new Property('path', $image->path()->toString()),
            new Property('query', $image->query()->toString()),
            new Property(
                'descriptions',
                $image
                    ->descriptions()
                    ->reduce(
                        Set::of('string'),
                        function(Set $carry, Description $description): Set {
                            return $carry->add((string) $description);
                        }
                    )
            ),
        ];

        if ($image->isDimensionKnown()) {
            /** @psalm-suppress InvalidArgument */
            $properties[] = new Property(
                'dimension',
                Map::of('string', 'int')
                    ('width', $image->dimension()->width())
                    ('height', $image->dimension()->height())
            );
        }

        if ($image->isWeightKnown()) {
            $properties[] = new Property('weight', $image->weight()->toInt());
        }

        return HttpResource\HttpResource::of($definition, ...$properties);
    }
}
