<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\ReferenceRepositoryInterface,
    Entity\Reference,
    Entity\Reference\IdentityInterface,
    Specification\Reference\SpecificationInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class ReferenceRepository implements ReferenceRepositoryInterface
{
    private $infrastructure;

    public function __construct(RepositoryInterface $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    public function get(IdentityInterface $identity): Reference
    {
        return $this->infrastructure->get($identity);
    }

    public function add(Reference $entity): ReferenceRepositoryInterface
    {
        $this->infrastructure->add($entity);

        return $this;
    }

    public function remove(IdentityInterface $identity): ReferenceRepositoryInterface
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
                new Set(Reference::class),
                function(Set $all, Reference $entity): Set {
                    return $all->add($entity);
                }
            );
    }

    /**
     * @return SetInterface<Reference>
     */
    public function matching(SpecificationInterface $specification): SetInterface
    {
        return $this
            ->infrastructure
            ->matching($specification)
            ->reduce(
                new Set(Reference::class),
                function(Set $all, Reference $entity): Set {
                    return $all->add($entity);
                }
            );
    }
}
