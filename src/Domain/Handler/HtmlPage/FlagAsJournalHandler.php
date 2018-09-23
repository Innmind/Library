<?php
declare(strict_types = 1);

namespace Domain\Handler\HtmlPage;

use Domain\{
    Command\HtmlPage\FlagAsJournal,
    Repository\HtmlPageRepository
};

final class FlagAsJournalHandler
{
    private $repository;

    public function __construct(HtmlPageRepository $repository)
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
