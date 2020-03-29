<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyMainContent,
    Repository\HtmlPageRepository
};

final class SpecifyMainContentHandler
{
    private HtmlPageRepository $repository;

    public function __construct(HtmlPageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyMainContent $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyMainContent($wished->mainContent());
    }
}
