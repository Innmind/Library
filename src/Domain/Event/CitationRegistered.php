<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\Entity\Citation\IdentityInterface;

final class CitationRegistered
{
    private $identity;
    private $text;

    public function __construct(IdentityInterface $identity, string $text)
    {
        $this->identity = $identity;
        $this->text = $text;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function text(): string
    {
        return $this->text;
    }
}
