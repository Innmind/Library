<?php
declare(strict_types = 1);

namespace App\Repository\Neo4j;

use Domain\{
    Repository\CanonicalRepository as CanonicalRepositoryInterface,
    Entity\Canonical,
    Entity\Canonical\Identity,
    Exception\CanonicalNotFound,
    Specification\Canonical\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class CanonicalRepository implements CanonicalRepositoryInterface
{
    private $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identity $identity): Canonical
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new CanonicalNotFound('', 0, $e);
        }
    }

    public function add(Canonical $entity): CanonicalRepositoryInterface
    {
        $this->infrastructure->add($entity);

        return $this;
    }

    public function remove(Identity $identity): CanonicalRepositoryInterface
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
                new Set(Canonical::class),
                function(Set $all, Canonical $entity): Set {
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
                new Set(Canonical::class),
                function(Set $all, Canonical $entity): Set {
                    return $all->add($entity);
                }
            );
    }
}