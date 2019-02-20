<?php
declare(strict_types = 1);

namespace Domain\Specification\Host;

use Domain\Entity\{
    Host,
    Host\Name as Model,
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

    public function isSatisfiedBy(Host $host): bool
    {
        return (string) $host->name() === $this->value;
    }
}
