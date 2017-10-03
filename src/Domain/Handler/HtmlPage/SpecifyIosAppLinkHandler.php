<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyIosAppLink,
    Repository\HtmlPageRepository
};

final class SpecifyIosAppLinkHandler
{
    private $repository;

    public function __construct(HtmlPageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SpecifyIosAppLink $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->specifyIosAppLink($wished->url());
    }
}
