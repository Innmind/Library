<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\DomainHost\Identity,
    Entity\DomainHost,
    Specification\DomainHost\Specification
};
use Innmind\Immutable\Set;

interface DomainHostRepository
{
    /**
     * @throws DomainHostNotFoundException
     */
    public function get(Identity $identity): DomainHost;
    public function add(DomainHost $domainHost): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return Set<DomainHost>
     */
    public function all(): Set;

    /**
     * @return Set<DomainHost>
     */
    public function matching(Specification $specification): Set;
}
