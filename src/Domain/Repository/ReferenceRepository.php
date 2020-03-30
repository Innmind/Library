<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Reference\Identity,
    Entity\Reference,
    Specification\Reference\Specification,
    Exception\ReferenceNotFound,
};
use Innmind\Immutable\Set;

interface ReferenceRepository
{
    /**
     * @throws ReferenceNotFound
     */
    public function get(Identity $identity): Reference;
    public function add(Reference $reference): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return Set<Reference>
     */
    public function all(): Set;

    /**
     * @return Set<Reference>
     */
    public function matching(Specification $specification): Set;
}
