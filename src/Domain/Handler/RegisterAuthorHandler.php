<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\RegisterAuthor,
    Repository\AuthorRepository,
    Entity\Author,
    Specification\Author\Name,
    Exception\AuthorAlreadyExist
};
use function Innmind\Immutable\first;

final class RegisterAuthorHandler
{
    private AuthorRepository $repository;

    public function __construct(AuthorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RegisterAuthor $wished): void
    {
        $authors = $this->repository->matching(new Name($wished->name()));

        if ($authors->size() > 0) {
            throw new AuthorAlreadyExist(first($authors));
        }

        $this->repository->add(
            Author::register(
                $wished->identity(),
                $wished->name()
            )
        );
    }
}
