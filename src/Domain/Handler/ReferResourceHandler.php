<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\ReferResource,
    Repository\ReferenceRepository,
    Entity\Reference,
    Specification\Reference\Source,
    Specification\Reference\Target,
    Exception\ReferenceAlreadyExist
};
use function Innmind\Immutable\first;

final class ReferResourceHandler
{
    private ReferenceRepository $repository;

    public function __construct(ReferenceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ReferResource $wished): void
    {
        /** @psalm-suppress InvalidArgument */
        $references = $this->repository->matching(
            (new Source($wished->source()))
                ->and(new Target($wished->target()))
        );

        if ($references->size() > 0) {
            throw new ReferenceAlreadyExist(first($references));
        }

        $this->repository->add(
            Reference::create(
                $wished->identity(),
                $wished->source(),
                $wished->target()
            )
        );
    }
}
