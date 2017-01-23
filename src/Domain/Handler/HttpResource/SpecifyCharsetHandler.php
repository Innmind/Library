<?php
declare(strict_types = 1);

namespace Domain\Handler\HttpResource;

use Domain\{
    Command\HttpResource\SpecifyCharset,
    Repository\HttpResourceRepositoryInterface
};

final class SpecifyCharsetHandler
{
    private $repository;

    public function __construct(HttpResourceRepositoryInterface $repository)
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
