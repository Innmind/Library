<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyAnchors,
    Repository\HtmlPageRepository
};

final class SpecifyAnchorsHandler
{
    private HtmlPageRepository $repository;

    public function __construct(HtmlPageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyAnchors $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyAnchors($wished->anchors());
    }
}
