<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Host\Identity,
    Entity\Host,
    Specification\Host\Specification
};
use Innmind\Immutable\SetInterface;

interface HostRepository
{
    /**
     * @throws HostNotFoundException
     */
    public function get(Identity $identity): Host;
    public function add(Host $host): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Host>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Host>
     */
    public function matching(Specification $specification): SetInterface;
}
