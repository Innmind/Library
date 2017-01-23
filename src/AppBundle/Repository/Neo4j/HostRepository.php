<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\HostRepositoryInterface,
    Entity\Host,
    Entity\Host\IdentityInterface,
    Specification\Host\SpecificationInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class HostRepository implements HostRepositoryInterface
{
    private $infrastructure;

    public function __construct(RepositoryInterface $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    public function get(IdentityInterface $identity): Host
    {
        return $this->infrastructure->get($identity);
    }

    public function add(Host $host): HostRepositoryInterface
    {
        $this->infrastructure->add($host);

        return $this;
    }

    public function remove(IdentityInterface $identity): HostRepositoryInterface
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
                new Set(Host::class),
                function(Set $all, Host $host): Set {
                    return $all->add($host);
                }
            );
    }

    /**
     * @return SetInterface<Host>
     */
    public function matching(SpecificationInterface $specification): SetInterface
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
