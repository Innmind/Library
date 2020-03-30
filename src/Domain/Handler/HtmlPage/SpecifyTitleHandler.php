<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyTitle,
    Repository\HtmlPageRepository
};

final class SpecifyTitleHandler
{
    private HtmlPageRepository $repository;

    public function __construct(HtmlPageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyTitle $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyTitle($wished->title());
    }
}
