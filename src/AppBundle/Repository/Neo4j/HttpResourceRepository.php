<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\HttpResourceRepositoryInterface,
    Entity\HttpResource,
    Entity\HttpResource\IdentityInterface,
    Specification\HttpResource\SpecificationInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class HttpResourceRepository implements HttpResourceRepositoryInterface
{
    private $infrastructure;

    public function __construct(RepositoryInterface $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    public function get(IdentityInterface $identity): HttpResource
    {
        return $this->infrastructure->get($identity);
    }

    public function add(HttpResource $resource): HttpResourceRepositoryInterface
    {
        $this->infrastructure->add($resource);

        return $this;
    }

    public function remove(IdentityInterface $identity): HttpResourceRepositoryInterface
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
                new Set(HttpResource::class),
                function(Set $all, HttpResource $resource): Set {
                    return $all->add($resource);
                }
            );
    }

    /**
     * @return SetInterface<HttpResource>
     */
    public function matching(SpecificationInterface $specification): SetInterface
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
