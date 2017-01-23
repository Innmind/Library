<?php
declare(strict_types = 1);

namespace Domain\Specification\Author;

use Domain\Entity\{
    Author,
    Author\Name as Model
};
use Innmind\Specification\ComparatorInterface;

final class Name implements ComparatorInterface, SpecificationInterface
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
    public function sign(): string
    {
        return '=';
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
