<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyPreview,
    Repository\HtmlPageRepositoryInterface
};

final class SpecifyPreviewHandler
{
    private $repository;

    public function __construct(HtmlPageRepositoryInterface $repository)
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
