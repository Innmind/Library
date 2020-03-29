<?php
declare(strict_types = 1);

namespace App\Repository\Neo4j;

use Domain\{
    Repository\CitationRepository as CitationRepositoryInterface,
    Entity\Citation,
    Entity\Citation\Identity,
    Exception\CitationNotFound,
    Specification\Citation\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\Set;

final class CitationRepository implements CitationRepositoryInterface
{
    private Repository $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identity $identity): Citation
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new CitationNotFound('', 0, $e);
        }
    }

    public function add(Citation $citation): CitationRepositoryInterface
    {
        $this->infrastructure->add($citation);

        return $this;
    }

    public function remove(Identity $identity): CitationRepositoryInterface
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
                Set::of(Citation::class),
                function(Set $all, Citation $citation): Set {
                    return $all->add($citation);
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
                Set::of(Citation::class),
                function(Set $all, Citation $citation): Set {
                    return $all->add($citation);
                }
            );
    }
}
