<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyTitle,
    Repository\HtmlPageRepositoryInterface
};

final class SpecifyTitleHandler
{
    private $repository;

    public function __construct(HtmlPageRepositoryInterface $repository)
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
