<?php
declare(strict_types = 1);

namespace Domain\Command\HttpResource;

use Domain\{
    Entity\HttpResource\Identity,
    Model\Language,
    Exception\InvalidArgumentException
};
use Innmind\Immutable\SetInterface;

final class SpecifyLanguages
{
    private $identity;
    private $languages;

    public function __construct(
        Identity $identity,
        SetInterface $languages
    ) {
        if ((string) $languages->type() !== Language::class) {
            throw new InvalidArgumentException;
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
