<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\ResourceAuthor\IdentityInterface,
    Entity\ResourceAuthor,
    Specification\ResourceAuthor\SpecificationInterface
};
use Innmind\Immutable\SetInterface;

interface ResourceAuthorRepositoryInterface
{
    public function get(IdentityInterface $identity): ResourceAuthor;
    public function add(ResourceAuthor $resourceAuthor): self;
    public function remove(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<ResourceAuthor>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<ResourceAuthor>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
