<?php
declare(strict_types = 1);

namespace App\Repository\Neo4j;

use Domain\{
    Repository\AuthorRepository as AuthorRepositoryInterface,
    Entity\Author,
    Entity\Author\Identity,
    Exception\AuthorNotFound,
    Specification\Author\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\Set;

final class AuthorRepository implements AuthorRepositoryInterface
{
    private Repository $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identity $identity): Author
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new AuthorNotFound('', 0, $e);
        }
    }

    public function add(Author $author): AuthorRepositoryInterface
    {
        $this->infrastructure->add($author);

        return $this;
    }

    public function remove(Identity $identity): AuthorRepositoryInterface
    {
        $this->infrastructure->remove(
            $this->get($identity)
        );

        return $this;
    }

    public function has(Identity $identity): bool
    {
        return $this->infrastructure->contains($identity);
    }

    public function count(): int
    {
        return $this->infrastructure->all()->size();
    }

    /**
     * {@inheritdoc}
     */
    public function all(): Set
    {
        return $this
            ->infrastructure
            ->all()
            ->reduce(
                Set::of(Author::class),
                function(Set $all, Author $author): Set {
                    return $all->add($author);
                }
            );
    }

    /**
     * {@inheritdoc}
     */
    public function matching(Specification $specification): Set
    {
        return $this
            ->infrastructure
            ->matching($specification)
            ->reduce(
                Set::of(Author::class),
                function(Set $all, Author $author): Set {
                    return $all->add($author);
                }
            );
    }
}
