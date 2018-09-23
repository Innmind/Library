<?php
declare(strict_types = 1);

namespace App\Repository\Neo4j;

use Domain\{
    Repository\CitationAppearanceRepository as CitationAppearanceRepositoryInterface,
    Entity\CitationAppearance,
    Entity\CitationAppearance\Identity,
    Exception\CitationAppearanceNotFound,
    Specification\CitationAppearance\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class CitationAppearanceRepository implements CitationAppearanceRepositoryInterface
{
    private $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identity $identity): CitationAppearance
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new CitationAppearanceNotFound('', 0, $e);
        }
    }

    public function add(CitationAppearance $entity): CitationAppearanceRepositoryInterface
    {
        $this->infrastructure->add($entity);

        return $this;
    }

    public function remove(Identity $identity): CitationAppearanceRepositoryInterface
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
                new Set(CitationAppearance::class),
                function(Set $all, CitationAppearance $entity): Set {
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
                new Set(CitationAppearance::class),
                function(Set $all, CitationAppearance $entity): Set {
                    return $all->add($entity);
                }
            );
    }
}
