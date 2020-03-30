<?php
declare(strict_types = 1);

namespace App\Repository\Neo4j;

use Domain\{
    Repository\ReferenceRepository as ReferenceRepositoryInterface,
    Entity\Reference,
    Entity\Reference\Identity,
    Exception\ReferenceNotFound,
    Specification\Reference\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\Set;

final class ReferenceRepository implements ReferenceRepositoryInterface
{
    private Repository $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * @psalm-suppress MoreSpecificReturnType
     */
    public function get(Identity $identity): Reference
    {
        try {
            /**
             * @psalm-suppress InvalidArgument
             * @psalm-suppress LessSpecificReturnStatement
             */
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new ReferenceNotFound('', 0, $e);
        }
    }

    public function add(Reference $entity): ReferenceRepositoryInterface
    {
        $this->infrastructure->add($entity);

        return $this;
    }

    public function remove(Identity $identity): ReferenceRepositoryInterface
    {
        $this->infrastructure->remove(
            $this->get($identity)
        );

        return $this;
    }

    public function has(Identity $identity): bool
    {
        /** @psalm-suppress InvalidArgument */
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
            ->toSetOf(Reference::class);
    }

    /**
     * {@inheritdoc}
     */
    public function matching(Specification $specification): Set
    {
        return $this
            ->infrastructure
            ->matching($specification)
            ->toSetOf(Reference::class);
    }
}
