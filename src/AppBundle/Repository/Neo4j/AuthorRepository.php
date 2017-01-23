<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\AuthorRepositoryInterface,
    Entity\Author,
    Entity\Author\IdentityInterface,
    Specification\Author\SpecificationInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class AuthorRepository implements AuthorRepositoryInterface
{
    private $infrastructure;

    public function __construct(RepositoryInterface $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    public function get(IdentityInterface $identity): Author
    {
        return $this->infrastructure->get($identity);
    }

    public function add(Author $author): AuthorRepositoryInterface
    {
        $this->infrastructure->add($author);

        return $this;
    }

    public function remove(IdentityInterface $identity): AuthorRepositoryInterface
    {
        $this->infrastructure->remove(
            $this->get($identity)
        );

        return $this;
    }

    public function has(IdentityInterface $identity): bool
    {
        return $this->infrastructure->has($identity);
    }

    public function count(): int
    {
        return $this->infrastructure->all()->size();
    }

    /**
     * {@inheritdoc}
     */
    public function all(): SetInterface
    {
        return $this
            ->infrastructure
            ->all()
            ->reduce(
                new Set(Author::class),
                function(Set $all, Author $author): Set {
                    return $all->add($author);
                }
            );
    }

    /**
     * @return SetInterface<Author>
     */
    public function matching(SpecificationInterface $specification): SetInterface
    {
        return $this
            ->infrastructure
            ->matching($specification)
            ->reduce(
                new Set(Author::class),
                function(Set $all, Author $author): Set {
                    return $all->add($author);
                }
            );
    }
}
