<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyDescription,
    Repository\HtmlPageRepository
};

final class SpecifyDescriptionHandler
{
    private HtmlPageRepository $repository;

    public function __construct(HtmlPageRepository $repository)
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
