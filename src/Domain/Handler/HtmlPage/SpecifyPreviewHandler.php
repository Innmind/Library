<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyPreview,
    Repository\HtmlPageRepository
};

final class SpecifyPreviewHandler
{
    private HtmlPageRepository $repository;

    public function __construct(HtmlPageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyPreview $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->usePreview($wished->url());
    }
}
