<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\HttpResourceRepository as HttpResourceRepositoryInterface,
    Entity\HttpResource,
    Entity\HttpResource\Identity,
    Exception\HttpResourceNotFoundException,
    Specification\HttpResource\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class HttpResourceRepository implements HttpResourceRepositoryInterface
{
    private $infrastructure;

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
            throw new HttpResourceNotFoundException('', 0, $e);
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
                new Set(HttpResource::class),
                function(Set $all, HttpResource $resource): Set {
                    return $all->add($resource);
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
                new Set(HttpResource::class),
                function(Set $all, HttpResource $resource): Set {
                    return $all->add($resource);
                }
            );
    }
}
