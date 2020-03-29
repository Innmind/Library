<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\ResourceAuthor\Identity,
    Entity\ResourceAuthor,
    Specification\ResourceAuthor\Specification
};
use Innmind\Immutable\Set;

interface ResourceAuthorRepository
{
    /**
     * @throws ResourceAuthorNotFoundException
     */
    public function get(Identity $identity): ResourceAuthor;
    public function add(ResourceAuthor $resourceAuthor): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return Set<ResourceAuthor>
     */
    public function all(): Set;

    /**
     * @return Set<ResourceAuthor>
     */
    public function matching(Specification $specification): Set;
}
