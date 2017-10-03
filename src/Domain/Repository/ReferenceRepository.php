<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Reference\Identity,
    Entity\Reference,
    Specification\Reference\Specification
};
use Innmind\Immutable\SetInterface;

interface ReferenceRepository
{
    /**
     * @throws ReferenceNotFoundException
     */
    public function get(Identity $identity): Reference;
    public function add(Reference $reference): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Reference>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Reference>
     */
    public function matching(Specification $specification): SetInterface;
}
