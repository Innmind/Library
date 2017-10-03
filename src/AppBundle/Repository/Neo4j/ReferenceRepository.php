<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\ReferenceRepository as ReferenceRepositoryInterface,
    Entity\Reference,
    Entity\Reference\Identity,
    Exception\ReferenceNotFoundException,
    Specification\Reference\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class ReferenceRepository implements ReferenceRepositoryInterface
{
    private $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identity $identity): Reference
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new ReferenceNotFoundException('', 0, $e);
        }
    }

    public function add(Reference $entity): ReferenceRepositoryInterface
    {
        $this->infrastructure->add($entity);

        return $this;
    }

    public function remove(Identity $identity): ReferenceRepositoryInterface
    {
        $this->infrastructure->remove(
            $this->get($identity)
        );

        return $this;
    }

    public function has(Identity $identity): bool
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
     * {@inheritdoc}
     */
    public function matching(Specification $specification): SetInterface
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
