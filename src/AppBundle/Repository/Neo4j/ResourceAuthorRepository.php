<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\ResourceAuthorRepositoryInterface,
    Entity\ResourceAuthor,
    Entity\ResourceAuthor\IdentityInterface,
    Specification\ResourceAuthor\SpecificationInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class ResourceAuthorRepository implements ResourceAuthorRepositoryInterface
{
    private $infrastructure;

    public function __construct(RepositoryInterface $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    public function get(IdentityInterface $identity): ResourceAuthor
    {
        return $this->infrastructure->get($identity);
    }

    public function add(ResourceAuthor $entity): ResourceAuthorRepositoryInterface
    {
        $this->infrastructure->add($entity);

        return $this;
    }

    public function remove(IdentityInterface $identity): ResourceAuthorRepositoryInterface
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
                new Set(ResourceAuthor::class),
                function(Set $all, ResourceAuthor $entity): Set {
                    return $all->add($entity);
                }
            );
    }

    /**
     * @return SetInterface<ResourceAuthor>
     */
    public function matching(SpecificationInterface $specification): SetInterface
    {
        return $this
            ->infrastructure
            ->matching($specification)
            ->reduce(
                new Set(ResourceAuthor::class),
                function(Set $all, ResourceAuthor $entity): Set {
                    return $all->add($entity);
                }
            );
    }
}
