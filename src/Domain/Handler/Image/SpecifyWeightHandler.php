<?php
declare(strict_types = 1);

namespace Domain\Handler\Image;

use Domain\{
    Command\Image\SpecifyWeight,
    Repository\ImageRepositoryInterface
};

final class SpecifyWeightHandler
{
    private $repository;

    public function __construct(ImageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyWeight $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyWeight($wished->weight());
    }
}
