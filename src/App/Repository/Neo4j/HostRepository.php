<?php
declare(strict_types = 1);

namespace App\Repository\Neo4j;

use Domain\{
    Repository\HostRepository as HostRepositoryInterface,
    Entity\Host,
    Entity\Host\Identity,
    Exception\HostNotFound,
    Specification\Host\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class HostRepository implements HostRepositoryInterface
{
    private $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identity $identity): Host
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new HostNotFound('', 0, $e);
        }
    }

    public function add(Host $host): HostRepositoryInterface
    {
        $this->infrastructure->add($host);

        return $this;
    }

    public function remove(Identity $identity): HostRepositoryInterface
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
                new Set(Host::class),
                function(Set $all, Host $host): Set {
                    return $all->add($host);
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
                new Set(Host::class),
                function(Set $all, Host $host): Set {
                    return $all->add($host);
                }
            );
    }
}
