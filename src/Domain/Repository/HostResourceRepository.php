<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\HostResource\Identity,
    Entity\HostResource,
    Specification\HostResource\Specification
};
use Innmind\Immutable\SetInterface;

interface HostResourceRepository
{
    /**
     * @throws HostResourceNotFoundException
     */
    public function get(Identity $identity): HostResource;
    public function add(HostResource $hostResource): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<HostResource>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<HostResource>
     */
    public function matching(Specification $specification): SetInterface;
}
