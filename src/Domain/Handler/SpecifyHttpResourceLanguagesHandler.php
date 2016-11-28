<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\SpecifyHttpResourceLanguages,
    Repository\HttpResourceRepositoryInterface
};

final class SpecifyHttpResourceLanguagesHandler
{
    private $repository;

    public function __construct(HttpResourceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyHttpResourceLanguages $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyLanguages($wished->languages());
    }
}
