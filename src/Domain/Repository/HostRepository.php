<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Host\Identity,
    Entity\Host,
    Specification\Host\Specification,
    Exception\HostNotFound,
};
use Innmind\Immutable\Set;

interface HostRepository
{
    /**
     * @throws HostNotFound
     */
    public function get(Identity $identity): Host;
    public function add(Host $host): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return Set<Host>
     */
    public function all(): Set;

    /**
     * @return Set<Host>
     */
    public function matching(Specification $specification): Set;
}
