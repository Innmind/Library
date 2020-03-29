<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Canonical\Identity,
    Entity\Canonical,
    Specification\Canonical\Specification
};
use Innmind\Immutable\Set;

interface CanonicalRepository
{
    /**
     * @throws CanonicalNotFoundException
     */
    public function get(Identity $identity): Canonical;
    public function add(Canonical $canonical): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return Set<Canonical>
     */
    public function all(): Set;

    /**
     * @return Set<Canonical>
     */
    public function matching(Specification $specification): Set;
}
