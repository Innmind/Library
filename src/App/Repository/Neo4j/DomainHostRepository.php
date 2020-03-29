<?php
declare(strict_types = 1);

namespace App\Repository\Neo4j;

use Domain\{
    Repository\DomainHostRepository as DomainHostRepositoryInterface,
    Entity\DomainHost,
    Entity\DomainHost\Identity,
    Exception\DomainHostNotFound,
    Specification\DomainHost\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\Set;

final class DomainHostRepository implements DomainHostRepositoryInterface
{
    private Repository $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identity $identity): DomainHost
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new DomainHostNotFound('', 0, $e);
        }
    }

    public function add(DomainHost $domainHost): DomainHostRepositoryInterface
    {
        $this->infrastructure->add($domainHost);

        return $this;
    }

    public function remove(Identity $identity): DomainHostRepositoryInterface
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
                Set::of(DomainHost::class),
                function(Set $all, DomainHost $domainHost): Set {
                    return $all->add($domainHost);
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
                Set::of(DomainHost::class),
                function(Set $all, DomainHost $domainHost): Set {
                    return $all->add($domainHost);
                }
            );
    }
}
