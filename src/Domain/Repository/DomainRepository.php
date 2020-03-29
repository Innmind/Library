<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Domain\Identity,
    Entity\Domain,
    Specification\Domain\Specification
};
use Innmind\Immutable\Set;

interface DomainRepository
{
    /**
     * @throws DomainNotFoundException
     */
    public function get(Identity $identity): Domain;
    public function add(Domain $domain): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return Set<Domain>
     */
    public function all(): Set;

    /**
     * @return Set<Domain>
     */
    public function matching(Specification $specification): Set;
}
