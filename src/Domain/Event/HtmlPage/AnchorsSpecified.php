<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\{
    Entity\HtmlPage\Identity,
    Entity\HtmlPage\Anchor
};
use Innmind\Immutable\Set;

final class AnchorsSpecified
{
    private Identity $identity;
    private Set $anchors;

    public function __construct(Identity $identity, Set $anchors)
    {
        if ((string) $anchors->type() !== Anchor::class) {
            throw new \TypeError(sprintf(
                'Argument 2 must be of type Set<%s>',
                Anchor::class
            ));
        }

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
