<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\RegisterCitation,
    Repository\CitationRepository,
    Entity\Citation,
    Exception\CitationAlreadyExistException,
    Specification\Citation\Text
};

final class RegisterCitationHandler
{
    private $repository;

    public function __construct(CitationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RegisterCitation $wished): void
    {
        $citations = $this->repository->matching(new Text($wished->text()));

        if ($citations->size() > 0) {
            throw new CitationAlreadyExistException($citations->current());
        }

        $this->repository->add(
            Citation::register(
                $wished->identity(),
                $wished->text()
            )
        );
    }
}
