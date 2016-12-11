<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Domain\IdentityInterface,
    Entity\Domain,
    Specification\Domain\SpecificationInterface
};
use Innmind\Immutable\SetInterface;

interface DomainRepositoryInterface
{
    public function get(IdentityInterface $identity): Domain;
    public function add(Domain $domain): self;
    public function remove(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Domain>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Domain>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
