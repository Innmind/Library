<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Alternate\Identity,
    Entity\Alternate,
    Specification\Alternate\Specification
};
use Innmind\Immutable\SetInterface;

interface AlternateRepository
{
    /**
     * @throws AlternateNotFoundException
     */
    public function get(Identity $identity): Alternate;
    public function add(Alternate $alternate): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Alternate>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Alternate>
     */
    public function matching(Specification $specification): SetInterface;
}
