<?php
declare(strict_types = 1);

namespace Domain\Specification\Domain;

use Domain\Entity\{
    Domain,
    Domain\TopLevelDomain as Model,
};
use Innmind\Specification\{
    Comparator,
    Sign,
};

final class TopLevelDomain implements Comparator, Specification
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
        return 'tld';
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
        return (string) $domain->tld() === $this->value;
    }
}
