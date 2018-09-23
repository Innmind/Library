<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\ResourceAuthor\Identity,
    Entity\ResourceAuthor,
    Specification\ResourceAuthor\Specification
};
use Innmind\Immutable\SetInterface;

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
     * @return SetInterface<ResourceAuthor>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<ResourceAuthor>
     */
    public function matching(Specification $specification): SetInterface;
}
