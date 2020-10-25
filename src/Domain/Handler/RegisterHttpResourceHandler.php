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
    Exception\HttpResourceAlreadyExist
};
use Innmind\TimeContinuum\Clock;
use Innmind\Immutable\Set;

final class RegisterHttpResourceHandler
{
    private HttpResourceRepository $resourceRepository;
    private HostResourceRepository $relationRepository;
    private Clock $clock;

    public function __construct(
        HttpResourceRepository $resourceRepository,
        HostResourceRepository $relationRepository,
        Clock $clock
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
     * @throws HttpResourceAlreadyExist
     */
    private function verifyResourceDoesntExist(RegisterHttpResource $wished): void
    {
        /** @psalm-suppress InvalidArgument */
        $resources = $this->resourceRepository->matching(
            (new Path($wished->path()))
                ->and(new Query($wished->query()))
        );

        if ($resources->size() === 0) {
            return;
        }

        /** @var Set<Identity> */
        $identities = $resources->reduce(
            Set::of(Identity::class),
            function(Set $identities, HttpResource $resource): Set {
                return $identities->add($resource->identity());
            }
        );
        /** @psalm-suppress InvalidArgument */
        $relations = $this->relationRepository->matching(
            (new InResources($identities))
                ->and(new Host($wished->host()))
        );

        if ($relations->size() > 0) {
            throw new HttpResourceAlreadyExist;
        }
    }
}
