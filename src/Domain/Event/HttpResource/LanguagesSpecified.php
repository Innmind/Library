<?php
declare(strict_types = 1);

namespace Domain\Event\HttpResource;

use Domain\{
    Entity\HttpResource\Identity,
    Model\Language
};
use Innmind\Immutable\Set;

final class LanguagesSpecified
{
    private Identity $identity;
    private Set $languages;

    public function __construct(
        Identity $identity,
        Set $languages
    ) {
        if ((string) $languages->type() !== Language::class) {
            throw new \TypeError(sprintf(
                'Argument 2 must be of type Set<%s>',
                Language::class
            ));
        }

        $this->identity = $identity;
        $this->languages = $languages;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function languages(): Set
    {
        return $this->languages;
    }
}
