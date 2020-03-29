<?php
declare(strict_types = 1);

namespace Domain\Command\HttpResource;

use Domain\{
    Entity\HttpResource\Identity,
    Model\Language
};
use Innmind\Immutable\SetInterface;

final class SpecifyLanguages
{
    private Identity $identity;
    private SetInterface $languages;

    public function __construct(
        Identity $identity,
        SetInterface $languages
    ) {
        if ((string) $languages->type() !== Language::class) {
            throw new \TypeError(sprintf(
                'Argument 2 must be of type SetInterface<%s>',
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

    public function languages(): SetInterface
    {
        return $this->languages;
    }
}
