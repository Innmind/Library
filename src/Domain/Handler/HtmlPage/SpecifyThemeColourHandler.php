<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyThemeColour,
    Repository\HtmlPageRepository
};

final class SpecifyThemeColourHandler
{
    private HtmlPageRepository $repository;

    public function __construct(HtmlPageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyThemeColour $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyThemeColour($wished->colour());
    }
}
