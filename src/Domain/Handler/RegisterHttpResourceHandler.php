<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\RegisterHttpResource,
    Entity\HttpResource,
    Entity\HostResource,
    Repository\HttpResourceRepositoryInterface,
    Repository\HostResourceRepositoryInterface
};
use Innmind\TimeContinuum\TimeContinuumInterface;

final class RegisterHttpResourceHandler
{
    private $resourceRepository;
    private $relationRepository;
    private $clock;

    public function __construct(
        HttpResourceRepositoryInterface $resourceRepository,
        HostResourceRepositoryInterface $relationRepository,
        TimeContinuumInterface $clock
    ) {
        $this->resourceRepository = $resourceRepository;
        $this->relationRepository = $relationRepository;
        $this->clock = $clock;
    }

    public function __invoke(RegisterHttpResource $wished): void
    {
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
}
