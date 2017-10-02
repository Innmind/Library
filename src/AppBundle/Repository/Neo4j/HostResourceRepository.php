<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\HostResourceRepositoryInterface,
    Entity\HostResource,
    Entity\HostResource\IdentityInterface,
    Exception\HostResourceNotFoundException,
    Specification\HostResource\SpecificationInterface
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class HostResourceRepository implements HostResourceRepositoryInterface
{
    private $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdentityInterface $identity): HostResource
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new HostResourceNotFoundException('', 0, $e);
        }
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
     * {@inheritdoc}
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
