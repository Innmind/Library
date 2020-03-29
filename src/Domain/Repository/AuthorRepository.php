<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Author\Identity,
    Entity\Author,
    Specification\Author\Specification
};
use Innmind\Immutable\Set;

interface AuthorRepository
{
    /**
     * @throws AuthorNotFoundException
     */
    public function get(Identity $identity): Author;
    public function add(Author $author): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return Set<Author>
     */
    public function all(): Set;

    /**
     * @return Set<Author>
     */
    public function matching(Specification $specification): Set;
}
