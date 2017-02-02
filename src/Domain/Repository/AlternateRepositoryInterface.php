<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Alternate\IdentityInterface,
    Entity\Alternate,
    Specification\Alternate\SpecificationInterface
};
use Innmind\Immutable\SetInterface;

interface AlternateRepositoryInterface
{
    /**
     * @throws AlternateNotFoundException
     */
    public function get(IdentityInterface $identity): Alternate;
    public function add(Alternate $alternate): self;
    public function remove(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Alternate>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Alternate>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
