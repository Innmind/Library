<?php
declare(strict_types = 1);

namespace Domain\Specification\Domain;

use Domain\{
    Specification\Composable,
    Entity\Domain\Name as Model
};
use Innmind\Specification\ComparatorInterface;

final class Name implements ComparatorInterface
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
}
