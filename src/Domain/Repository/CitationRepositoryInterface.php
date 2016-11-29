<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\Entity\{
    Citation\IdentityInterface,
    Citation
};
use Innmind\Immutable\SetInterface;
use Innmind\Specification\SpecificationInterface;

interface CitationRepositoryInterface
{
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
