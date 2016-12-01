<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\AddImageDescription,
    Repository\ImageRepositoryInterface
};

final class AddImageDescriptionHandler
{
    private $repository;

    public function __construct(ImageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(AddImageDescription $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->addDescription($wished->description());
    }
}
