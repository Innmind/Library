<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyAndroidAppLink,
    Repository\HtmlPageRepositoryInterface
};

final class SpecifyAndroidAppLinkHandler
{
    private $repository;

    public function __construct(HtmlPageRepositoryInterface $repository)
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
