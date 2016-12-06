<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\Entity\{
    Canonical\IdentityInterface,
    Canonical
};
use Innmind\Immutable\SetInterface;
use Innmind\Specification\SpecificationInterface;

interface CanonicalRepositoryInterface
{
    public function get(IdentityInterface $identity): Canonical;
    public function add(Canonical $canonical): self;
    public function remove(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Canonical>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Canonical>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
