<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\Entity\{
    DomainHost\IdentityInterface,
    DomainHost
};
use Innmind\Immutable\SetInterface;
use Innmind\Specification\SpecificationInterface;

interface DomainHostRepositoryInterface
{
    public function get(IdentityInterface $identity): DomainHost;
    public function add(DomainHost $domainHost): self;
    public function remove(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<DomainHost>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<DomainHost>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
