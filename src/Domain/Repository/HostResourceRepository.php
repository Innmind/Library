<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\HostResource\Identity,
    Entity\HostResource,
    Specification\HostResource\Specification,
    Exception\HostResourceNotFound,
};
use Innmind\Immutable\Set;

interface HostResourceRepository
{
    /**
     * @throws HostResourceNotFound
     */
    public function get(Identity $identity): HostResource;
    public function add(HostResource $hostResource): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return Set<HostResource>
     */
    public function all(): Set;

    /**
     * @return Set<HostResource>
     */
    public function matching(Specification $specification): Set;
}
