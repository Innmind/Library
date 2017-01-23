<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\RegisterAuthor,
    Repository\AuthorRepositoryInterface,
    Entity\Author,
    Specification\Author\Name,
    Exception\AuthorAlreadyExistException
};

final class RegisterAuthorHandler
{
    private $repository;

    public function __construct(AuthorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RegisterAuthor $wished): void
    {
        $authors = $this->repository->matching(new Name($wished->name()));

        if ($authors->size() > 0) {
            throw new AuthorAlreadyExistException($authors->current());
        }

        $this->repository->add(
            Author::register(
                $wished->identity(),
                $wished->name()
            )
        );
    }
}
