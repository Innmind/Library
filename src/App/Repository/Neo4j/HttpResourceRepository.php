<?php
declare(strict_types = 1);

namespace App\Repository\Neo4j;

use Domain\{
    Repository\HttpResourceRepository as HttpResourceRepositoryInterface,
    Entity\HttpResource,
    Entity\HttpResource\Identity,
    Exception\HttpResourceNotFound,
    Specification\HttpResource\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\Set;

final class HttpResourceRepository implements HttpResourceRepositoryInterface
{
    private Repository $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identity $identity): HttpResource
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new HttpResourceNotFound('', 0, $e);
        }
    }

    public function add(HttpResource $resource): HttpResourceRepositoryInterface
    {
        $this->infrastructure->add($resource);

        return $this;
    }

    public function remove(Identity $identity): HttpResourceRepositoryInterface
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
                Set::of(HttpResource::class),
                function(Set $all, HttpResource $resource): Set {
                    return $all->add($resource);
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
                Set::of(HttpResource::class),
                function(Set $all, HttpResource $resource): Set {
                    return $all->add($resource);
                }
            );
    }
}
