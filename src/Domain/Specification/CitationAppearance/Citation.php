<?php
declare(strict_types = 1);

namespace Domain\Specification\CitationAppearance;

use Domain\Entity\{
    CitationAppearance,
    Citation\Identity,
};
use Innmind\Specification\{
    Comparator,
    Sign,
};

final class Citation implements Comparator, Specification
{
    use Composable;

    private $value;

    public function __construct(Identity $value)
    {
        $this->value = (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function property(): string
    {
        return 'citation';
    }

    /**
     * {@inheritdoc}
     */
    public function sign(): Sign
    {
        return Sign::equality();
    }

    /**
     * {@inheritdoc}
     */
    public function value()
    {
        return $this->value;
    }

    public function isSatisfiedBy(CitationAppearance $appearance): bool
    {
        return (string) $appearance->citation() === $this->value;
    }
}
