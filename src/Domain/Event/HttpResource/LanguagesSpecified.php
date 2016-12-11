<?php
declare(strict_types = 1);

namespace Domain\Event\HttpResource;

use Domain\{
    Entity\HttpResource\IdentityInterface,
    Model\Language,
    Exception\InvalidArgumentException
};
use Innmind\Immutable\SetInterface;

final class LanguagesSpecified
{
    private $identity;
    private $languages;

    public function __construct(
        IdentityInterface $identity,
        SetInterface $languages
    ) {
        if ((string) $languages->type() !== Language::class) {
            throw new InvalidArgumentException;
        }

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
