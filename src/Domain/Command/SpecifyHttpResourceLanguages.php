<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\HttpResource\IdentityInterface;
use Innmind\Immutable\SetInterface;

final class SpecifyHttpResourceLanguages
{
    private $identity;
    private $languages;

    public function __construct(
        IdentityInterface $identity,
        SetInterface $languages
    ) {
        $this->identity = $identity;
        $this->languages = $languages;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function languages(): SetInterface
    {
        return $this->languages;
    }
}
