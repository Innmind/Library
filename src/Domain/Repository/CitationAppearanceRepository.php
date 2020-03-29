<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\CitationAppearance\Identity,
    Entity\CitationAppearance,
    Specification\CitationAppearance\Specification
};
use Innmind\Immutable\Set;

interface CitationAppearanceRepository
{
    /**
     * @throws CitationAppearanceNotFoundException
     */
    public function get(Identity $identity): CitationAppearance;
    public function add(CitationAppearance $citationAppearance): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return Set<CitationAppearance>
     */
    public function all(): Set;

    /**
     * @return Set<CitationAppearance>
     */
    public function matching(Specification $specification): Set;
}
