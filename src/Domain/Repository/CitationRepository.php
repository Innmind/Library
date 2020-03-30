<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Citation\Identity,
    Entity\Citation,
    Specification\Citation\Specification,
    Exception\CitationNotFound,
};
use Innmind\Immutable\Set;

interface CitationRepository
{
    /**
     * @throws CitationNotFound
     */
    public function get(Identity $identity): Citation;
    public function add(Citation $citation): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return Set<Citation>
     */
    public function all(): Set;

    /**
     * @return Set<Citation>
     */
    public function matching(Specification $specification): Set;
}
