<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\RegisterHttpResource,
    Entity\HttpResource,
    Entity\HostResource,
    Entity\HttpResource\Identity,
    Repository\HttpResourceRepository,
    Repository\HostResourceRepository,
    Specification\HttpResource\Path,
    Specification\HttpResource\Query,
    Specification\HostResource\InResources,
    Specification\HostResource\Host,
    Exception\HttpResourceAlreadyExistException
};
use Innmind\TimeContinuum\TimeContinuumInterface;
use Innmind\Immutable\Set;

final class RegisterHttpResourceHandler
{
    private $resourceRepository;
    private $relationRepository;
    private $clock;

    public function __construct(
        HttpResourceRepository $resourceRepository,
        HostResourceRepository $relationRepository,
        TimeContinuumInterface $clock
    ) {
        $this->resourceRepository = $resourceRepository;
        $this->relationRepository = $relationRepository;
        $this->clock = $clock;
    }

    public function __invoke(RegisterHttpResource $wished): void
    {
        $this->verifyResourceDoesntExist($wished);

        $resource = HttpResource::register(
            $wished->identity(),
            $wished->path(),
            $wished->query()
        );
        $relation = HostResource::create(
            $wished->relation(),
            $wished->host(),
            $wished->identity(),
            $this->clock->now()
        );

        $this->resourceRepository->add($resource);
        $this->relationRepository->add($relation);
    }

    /**
     * @throws HttpResourceAlreadyExistException
     */
    private function verifyResourceDoesntExist(RegisterHttpResource $wished): void
    {
        $resources = $this->resourceRepository->matching(
            (new Path($wished->path()))
                ->and(new Query($wished->query()))
        );

        if ($resources->size() === 0) {
            return;
        }

        $identities = $resources->reduce(
            new Set(Identity::class),
            function(Set $identities, HttpResource $resource): Set {
                return $identities->add($resource->identity());
            }
        );
        $relations = $this->relationRepository->matching(
            (new InResources($identities))
                ->and(new Host($wished->host()))
        );

        if ($relations->size() > 0) {
            throw new HttpResourceAlreadyExistException;
        }
    }
}
