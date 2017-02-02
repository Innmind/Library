<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Citation\IdentityInterface,
    Entity\Citation,
    Specification\Citation\SpecificationInterface
};
use Innmind\Immutable\SetInterface;

interface CitationRepositoryInterface
{
    /**
     * @throws CitationNotFoundException
     */
    public function get(IdentityInterface $identity): Citation;
    public function add(Citation $citation): self;
    public function remove(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Citation>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Citation>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
