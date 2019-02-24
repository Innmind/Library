<?php
declare(strict_types = 1);

namespace Domain\Specification\Author;

use Domain\Entity\{
    Author,
    Author\Name as Model,
};
use Innmind\Specification\{
    Comparator,
    Sign,
};

final class Name implements Comparator, Specification
{
    use Composable;

    private $value;

    public function __construct(Model $value)
    {
        $this->value = (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function property(): string
    {
        return 'name';
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

    public function isSatisfiedBy(Author $author): bool
    {
        return (string) $author->name() === $this->value;
    }
}
