<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\Entity\{
    CitationAppearance\IdentityInterface,
    CitationAppearance
};
use Innmind\Immutable\SetInterface;
use Innmind\Specification\SpecificationInterface;

interface CitationAppearanceRepositoryInterface
{
    public function get(IdentityInterface $identity): CitationAppearance;
    public function add(CitationAppearance $citationAppearance): self;
    public function remove(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<CitationAppearance>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<CitationAppearance>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
