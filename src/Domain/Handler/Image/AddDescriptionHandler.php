<?php
declare(strict_types = 1);

namespace Domain\Handler\Image;

use Domain\{
    Command\Image\AddDescription,
    Repository\ImageRepositoryInterface
};

final class AddDescriptionHandler
{
    private $repository;

    public function __construct(ImageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(AddDescription $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->addDescription($wished->description());
    }
}
