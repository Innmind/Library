<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\DeclareResourceAuthor,
    Repository\ResourceAuthorRepositoryInterface,
    Entity\ResourceAuthor
};
use Innmind\TimeContinuum\TimeContinuumInterface;

final class DeclareResourceAuthorHandler
{
    private $repository;
    private $clock;

    public function __construct(
        ResourceAuthorRepositoryInterface $repository,
        TimeContinuumInterface $clock
    ) {
        $this->repository = $repository;
        $this->clock = $clock;
    }

    public function __invoke(DeclareResourceAuthor $wished): void
    {
        $this->repository->add(
            ResourceAuthor::declare(
                $wished->identity(),
                $wished->author(),
                $wished->resource(),
                $this->clock->now()
            )
        );
    }
}
