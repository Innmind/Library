<?php
declare(strict_types = 1);

namespace Domain\Handler\HttpResource;

use Domain\{
    Command\HttpResource\SpecifyCharset,
    Repository\HttpResourceRepository
};

final class SpecifyCharsetHandler
{
    private HttpResourceRepository $repository;

    public function __construct(HttpResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyCharset $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyCharset($wished->charset());
    }
}
