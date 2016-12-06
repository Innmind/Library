<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\MakeCanonicalLink,
    Repository\CanonicalRepositoryInterface,
    Entity\Canonical,
    Specification\Canonical\HttpResource,
    Specification\Canonical\Canonical as CanonicalSpec,
    Exception\CanonicalAlreadyExistException
};

final class MakeCanonicalLinkHandler
{
    private $repository;

    public function __construct(CanonicalRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(MakeCanonicalLink $wished): void
    {
        $canonicals = $this->repository->matching(
            (new HttpResource($wished->resource()))
                ->and(new CanonicalSpec($wished->canonical()))
        );

        if ($canonicals->size() > 0) {
            throw new CanonicalAlreadyExistException;
        }

        $this->repository->add(
            Canonical::create(
                $wished->identity(),
                $wished->canonical(),
                $wished->resource()
            )
        );
    }
}
