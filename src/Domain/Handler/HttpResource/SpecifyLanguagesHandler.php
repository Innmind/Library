<?php
declare(strict_types = 1);

namespace Domain\Handler\HttpResource;

use Domain\{
    Command\HttpResource\SpecifyLanguages,
    Repository\HttpResourceRepositoryInterface
};

final class SpecifyLanguagesHandler
{
    private $repository;

    public function __construct(HttpResourceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyLanguages $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyLanguages($wished->languages());
    }
}
