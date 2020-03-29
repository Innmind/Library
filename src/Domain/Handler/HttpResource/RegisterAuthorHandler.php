<?php
declare(strict_types = 1);

namespace Domain\Handler\HttpResource;

use Domain\{
    Command\HttpResource\RegisterAuthor,
    Repository\ResourceAuthorRepository,
    Entity\ResourceAuthor
};
use Innmind\TimeContinuum\TimeContinuumInterface;

final class RegisterAuthorHandler
{
    private ResourceAuthorRepository $repository;
    private TimeContinuumInterface $clock;

    public function __construct(
        ResourceAuthorRepository $repository,
        TimeContinuumInterface $clock
    ) {
        $this->repository = $repository;
        $this->clock = $clock;
    }

    public function __invoke(RegisterAuthor $wished): void
    {
        $this->repository->add(
            ResourceAuthor::register(
                $wished->identity(),
                $wished->author(),
                $wished->resource(),
                $this->clock->now()
            )
        );
    }
}
