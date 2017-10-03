<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\ReferResource,
    Repository\ReferenceRepository,
    Entity\Reference,
    Specification\Reference\Source,
    Specification\Reference\Target,
    Exception\ReferenceAlreadyExistException
};

final class ReferResourceHandler
{
    private $repository;

    public function __construct(ReferenceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ReferResource $wished): void
    {
        $references = $this->repository->matching(
            (new Source($wished->source()))
                ->and(new Target($wished->target()))
        );

        if ($references->size() > 0) {
            throw new ReferenceAlreadyExistException($references->current());
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
