<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\AlternateRepositoryInterface,
    Entity\Alternate,
    Entity\Alternate\IdentityInterface,
    Specification\Alternate\SpecificationInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class AlternateRepository implements AlternateRepositoryInterface
{
    private $infrastructure;

    public function __construct(RepositoryInterface $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    public function get(IdentityInterface $identity): Alternate
    {
        return $this->infrastructure->get($identity);
    }

    public function add(Alternate $entity): AlternateRepositoryInterface
    {
        $this->infrastructure->add($entity);

        return $this;
    }

    public function remove(IdentityInterface $identity): AlternateRepositoryInterface
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
                new Set(Alternate::class),
                function(Set $all, Alternate $entity): Set {
                    return $all->add($entity);
                }
            );
    }

    /**
     * @return SetInterface<Alternate>
     */
    public function matching(SpecificationInterface $specification): SetInterface
    {
        return $this
            ->infrastructure
            ->matching($specification)
            ->reduce(
                new Set(Alternate::class),
                function(Set $all, Alternate $entity): Set {
                    return $all->add($entity);
                }
            );
    }
}
