<?php
declare(strict_types = 1);

namespace App\Repository\Neo4j;

use Domain\{
    Repository\AlternateRepository as AlternateRepositoryInterface,
    Entity\Alternate,
    Entity\Alternate\Identity,
    Exception\AlternateNotFound,
    Specification\Alternate\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class AlternateRepository implements AlternateRepositoryInterface
{
    private $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identity $identity): Alternate
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new AlternateNotFound('', 0, $e);
        }
    }

    public function add(Alternate $entity): AlternateRepositoryInterface
    {
        $this->infrastructure->add($entity);

        return $this;
    }

    public function remove(Identity $identity): AlternateRepositoryInterface
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
                new Set(Alternate::class),
                function(Set $all, Alternate $entity): Set {
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
                new Set(Alternate::class),
                function(Set $all, Alternate $entity): Set {
                    return $all->add($entity);
                }
            );
    }
}
