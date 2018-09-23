<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Domain\Identity,
    Entity\Domain,
    Specification\Domain\Specification
};
use Innmind\Immutable\SetInterface;

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
     * @return SetInterface<Domain>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Domain>
     */
    public function matching(Specification $specification): SetInterface;
}
