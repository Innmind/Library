<?php
declare(strict_types = 1);

namespace Domain\Specification\Domain;

use Domain\Entity\{
    Domain,
    Domain\Name as Model,
};
use Innmind\Specification\{
    Comparator,
    Sign,
};

final class Name implements Comparator, Specification
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

    public function isSatisfiedBy(Domain $domain): bool
    {
        return (string) $domain->name() === $this->value;
    }
}
