<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\HostResourceRepositoryInterface,
    Entity\HostResource,
    Entity\HostResource\IdentityInterface,
    Specification\HostResource\SpecificationInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class HostResourceRepository implements HostResourceRepositoryInterface
{
    private $infrastructure;

    public function __construct(RepositoryInterface $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    public function get(IdentityInterface $identity): HostResource
    {
        return $this->infrastructure->get($identity);
    }

    public function add(HostResource $hostResource): HostResourceRepositoryInterface
    {
        $this->infrastructure->add($hostResource);

        return $this;
    }

    public function remove(IdentityInterface $identity): HostResourceRepositoryInterface
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
                new Set(HostResource::class),
                function(Set $all, HostResource $hostResource): Set {
                    return $all->add($hostResource);
                }
            );
    }

    /**
     * @return SetInterface<HostResource>
     */
    public function matching(SpecificationInterface $specification): SetInterface
    {
        return $this
            ->infrastructure
            ->matching($specification)
            ->reduce(
                new Set(HostResource::class),
                function(Set $all, HostResource $hostResource): Set {
                    return $all->add($hostResource);
                }
            );
    }
}
