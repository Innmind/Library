<?php
declare(strict_types = 1);

namespace App\Repository\Neo4j;

use Domain\{
    Repository\DomainRepository as DomainRepositoryInterface,
    Entity\Domain,
    Entity\Domain\Identity,
    Exception\DomainNotFound,
    Specification\Domain\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\Set;

final class DomainRepository implements DomainRepositoryInterface
{
    private Repository $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * @psalm-suppress MoreSpecificReturnType
     */
    public function get(Identity $identity): Domain
    {
        try {
            /**
             * @psalm-suppress InvalidArgument
             * @psalm-suppress LessSpecificReturnStatement
             */
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new DomainNotFound('', 0, $e);
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
            ->toSetOf(Domain::class);
    }

    /**
     * {@inheritdoc}
     */
    public function matching(Specification $specification): Set
    {
        return $this
            ->infrastructure
            ->matching($specification)
            ->toSetOf(Domain::class);
    }
}
