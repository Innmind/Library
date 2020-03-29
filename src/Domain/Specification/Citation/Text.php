<?php
declare(strict_types = 1);

namespace Domain\Specification\Citation;

use Domain\Entity\{
    Citation,
    Citation\Text as Model,
};
use Innmind\Specification\{
    Comparator,
    Sign,
};

final class Text implements Comparator, Specification
{
    use Composable;

    private string $value;

    public function __construct(Model $value)
    {
        $this->value = (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function property(): string
    {
        return 'text';
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

    public function isSatisfiedBy(Citation $citation): bool
    {
        return (string) $citation->text() === $this->value;
    }
}
