<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Author\IdentityInterface,
    Entity\Author,
    Specification\Author\SpecificationInterface
};
use Innmind\Immutable\SetInterface;

interface AuthorRepositoryInterface
{
    /**
     * @throws AuthorNotFoundException
     */
    public function get(IdentityInterface $identity): Author;
    public function add(Author $author): self;
    public function remove(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Author>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Author>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
