<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\CanonicalRepositoryInterface,
    Entity\Canonical,
    Entity\Canonical\IdentityInterface,
    Specification\Canonical\SpecificationInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class CanonicalRepository implements CanonicalRepositoryInterface
{
    private $infrastructure;

    public function __construct(RepositoryInterface $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    public function get(IdentityInterface $identity): Canonical
    {
        return $this->infrastructure->get($identity);
    }

    public function add(Canonical $entity): CanonicalRepositoryInterface
    {
        $this->infrastructure->add($entity);

        return $this;
    }

    public function remove(IdentityInterface $identity): CanonicalRepositoryInterface
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
                new Set(Canonical::class),
                function(Set $all, Canonical $entity): Set {
                    return $all->add($entity);
                }
            );
    }

    /**
     * @return SetInterface<Canonical>
     */
    public function matching(SpecificationInterface $specification): SetInterface
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
