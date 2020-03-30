<?php
declare(strict_types = 1);

namespace Domain\Event\HttpResource;

use Domain\{
    Entity\HttpResource\Identity,
    Model\Language
};
use Innmind\Immutable\Set;
use function Innmind\Immutable\assertSet;

final class LanguagesSpecified
{
    private Identity $identity;
    private Set $languages;

    public function __construct(Identity $identity, Set $languages)
    {
        assertSet(Language::class, $languages, 2);

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
