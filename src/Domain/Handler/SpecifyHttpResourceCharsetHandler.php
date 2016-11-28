<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\SpecifyHttpResourceCharset,
    Repository\HttpResourceRepositoryInterface
};

final class SpecifyHttpResourceCharsetHandler
{
    private $repository;

    public function __construct(HttpResourceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyHttpResourceCharset $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyCharset($wished->charset());
    }
}
