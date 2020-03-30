<?php
declare(strict_types = 1);

namespace Domain\Command\HtmlPage;

use Domain\{
    Entity\HtmlPage\Identity,
    Entity\HtmlPage\Anchor
};
use Innmind\Immutable\Set;
use function Innmind\Immutable\assertSet;

final class SpecifyAnchors
{
    private Identity $identity;
    /** @var Set<Anchor> */
    private Set $anchors;

    /**
     * @param Set<Anchor> $anchors
     */
    public function __construct(Identity $identity, Set $anchors)
    {
        assertSet(Anchor::class, $anchors, 2);

        $this->identity = $identity;
        $this->anchors = $anchors;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    /**
     * @return Set<Anchor>
     */
    public function anchors(): Set
    {
        return $this->anchors;
    }
}
