<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\{
    Entity\HtmlPage\Identity,
    Entity\HtmlPage\Anchor
};
use Innmind\Immutable\Set;
use function Innmind\Immutable\assertSet;

final class AnchorsSpecified
{
    private Identity $identity;
    private Set $anchors;

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

    public function anchors(): Set
    {
        return $this->anchors;
    }
}
