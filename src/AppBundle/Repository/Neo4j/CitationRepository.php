<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\CitationRepositoryInterface,
    Entity\Citation,
    Entity\Citation\IdentityInterface,
    Specification\Citation\SpecificationInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class CitationRepository implements CitationRepositoryInterface
{
    private $infrastructure;

    public function __construct(RepositoryInterface $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    public function get(IdentityInterface $identity): Citation
    {
        return $this->infrastructure->get($identity);
    }

    public function add(Citation $citation): CitationRepositoryInterface
    {
        $this->infrastructure->add($citation);

        return $this;
    }

    public function remove(IdentityInterface $identity): CitationRepositoryInterface
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
                new Set(Citation::class),
                function(Set $all, Citation $citation): Set {
                    return $all->add($citation);
                }
            );
    }

    /**
     * @return SetInterface<Citation>
     */
    public function matching(SpecificationInterface $specification): SetInterface
    {
        return $this
            ->infrastructure
            ->matching($specification)
            ->reduce(
                new Set(Citation::class),
                function(Set $all, Citation $author): Set {
                    return $all->add($author);
                }
            );
    }
}
