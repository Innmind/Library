<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\Entity\{
    Host\IdentityInterface,
    Host
};
use Innmind\Immutable\SetInterface;
use Innmind\Specification\SpecificationInterface;

interface HostRepositoryInterface
{
    public function get(IdentityInterface $identity): Host;
    public function add(Host $host): self;
    public function remove(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Host>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Host>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
