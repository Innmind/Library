<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\Entity\HtmlPage\IdentityInterface;
use Innmind\Immutable\SetInterface;

final class AnchorsSpecified
{
    private $identity;
    private $anchors;

    public function __construct(IdentityInterface $identity, SetInterface $anchors)
    {
        $this->identity = $identity;
        $this->anchors = $anchors;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function anchors(): SetInterface
    {
        return $this->anchors;
    }
}
