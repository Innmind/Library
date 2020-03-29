<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyAndroidAppLink,
    Repository\HtmlPageRepository
};

final class SpecifyAndroidAppLinkHandler
{
    private HtmlPageRepository $repository;

    public function __construct(HtmlPageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyAndroidAppLink $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyAndroidAppLink($wished->url());
    }
}
