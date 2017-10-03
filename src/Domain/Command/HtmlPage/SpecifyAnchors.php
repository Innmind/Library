<?php
declare(strict_types = 1);

namespace Domain\Command\HtmlPage;

use Domain\{
    Entity\HtmlPage\Identity,
    Entity\HtmlPage\Anchor,
    Exception\InvalidArgumentException
};
use Innmind\Immutable\SetInterface;

final class SpecifyAnchors
{
    private $identity;
    private $anchors;

    public function __construct(
        Identity $identity,
        SetInterface $anchors
    ) {
        if ((string) $anchors->type() !== Anchor::class) {
            throw new InvalidArgumentException;
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
