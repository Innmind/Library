<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\FlagAsJournal,
    Repository\HtmlPageRepositoryInterface
};

final class FlagAsJournalHandler
{
    private $repository;

    public function __construct(HtmlPageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FlagAsJournal $wished): void
    {
        $this
            ->repository
            ->get($wished->identity())
            ->flagAsJournal();
    }
}
