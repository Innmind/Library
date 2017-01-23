<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyDescription,
    Repository\HtmlPageRepositoryInterface
};

final class SpecifyDescriptionHandler
{
    private $repository;

    public function __construct(HtmlPageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyDescription $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyDescription($wished->description());
    }
}
