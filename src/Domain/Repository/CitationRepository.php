<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Citation\Identity,
    Entity\Citation,
    Specification\Citation\Specification
};
use Innmind\Immutable\SetInterface;

interface CitationRepository
{
    /**
     * @throws CitationNotFoundException
     */
    public function get(Identity $identity): Citation;
    public function add(Citation $citation): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Citation>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Citation>
     */
    public function matching(Specification $specification): SetInterface;
}
