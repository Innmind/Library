<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\RegisterAlternateResource,
    Repository\AlternateRepositoryInterface,
    Entity\Alternate,
    Specification\Alternate\HttpResource,
    Specification\Alternate\Alternate as AlternateSpec,
    Specification\Alternate\Language,
    Exception\AlternateAlreadyExistException
};

final class RegisterAlternateResourceHandler
{
    private $repository;

    public function __construct(AlternateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RegisterAlternateResource $wished): void
    {
        $alternates = $this->repository->matching(
            (new HttpResource($wished->resource()))
                ->and(new AlternateSpec($wished->alternate()))
                ->and(new Language($wished->language()))
        );

        if ($alternates->size() > 0) {
            throw new AlternateAlreadyExistException;
        }

        $this->repository->add(
            Alternate::create(
                $wished->identity(),
                $wished->resource(),
                $wished->alternate(),
                $wished->language()
            )
        );
    }
}
