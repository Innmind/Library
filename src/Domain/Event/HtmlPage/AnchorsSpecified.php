<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\{
    Entity\HtmlPage\Identity,
    Entity\HtmlPage\Anchor
};
use Innmind\Immutable\SetInterface;

final class AnchorsSpecified
{
    private $identity;
    private $anchors;

    public function __construct(Identity $identity, SetInterface $anchors)
    {
        if ((string) $anchors->type() !== Anchor::class) {
            throw new \TypeError(sprintf(
                'Argument 2 must be of type SetInterface<%s>',
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

    public function anchors(): SetInterface
    {
        return $this->anchors;
    }
}
