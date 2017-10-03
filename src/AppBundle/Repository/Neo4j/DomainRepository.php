<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\DomainRepository as DomainRepositoryInterface,
    Entity\Domain,
    Entity\Domain\Identity,
    Exception\DomainNotFoundException,
    Specification\Domain\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class DomainRepository implements DomainRepositoryInterface
{
    private $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identity $identity): Domain
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new DomainNotFoundException('', 0, $e);
        }
    }

    public function add(Domain $domain): DomainRepositoryInterface
    {
        $this->infrastructure->add($domain);

        return $this;
    }

    public function remove(Identity $identity): DomainRepositoryInterface
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
                new Set(Domain::class),
                function(Set $all, Domain $domain): Set {
                    return $all->add($domain);
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
                new Set(Domain::class),
                function(Set $all, Domain $domain): Set {
                    return $all->add($domain);
                }
            );
    }
}
