<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\DomainHost\Identity,
    Entity\DomainHost,
    Specification\DomainHost\Specification
};
use Innmind\Immutable\SetInterface;

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
     * @return SetInterface<DomainHost>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<DomainHost>
     */
    public function matching(Specification $specification): SetInterface;
}
