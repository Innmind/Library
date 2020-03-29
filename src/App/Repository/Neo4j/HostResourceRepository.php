<?php
declare(strict_types = 1);

namespace App\Repository\Neo4j;

use Domain\{
    Repository\HostResourceRepository as HostResourceRepositoryInterface,
    Entity\HostResource,
    Entity\HostResource\Identity,
    Exception\HostResourceNotFound,
    Specification\HostResource\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\Set;

final class HostResourceRepository implements HostResourceRepositoryInterface
{
    private Repository $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identity $identity): HostResource
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new HostResourceNotFound('', 0, $e);
        }
    }

    public function add(HostResource $hostResource): HostResourceRepositoryInterface
    {
        $this->infrastructure->add($hostResource);

        return $this;
    }

    public function remove(Identity $identity): HostResourceRepositoryInterface
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
                Set::of(HostResource::class),
                function(Set $all, HostResource $hostResource): Set {
                    return $all->add($hostResource);
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
                Set::of(HostResource::class),
                function(Set $all, HostResource $hostResource): Set {
                    return $all->add($hostResource);
                }
            );
    }
}
