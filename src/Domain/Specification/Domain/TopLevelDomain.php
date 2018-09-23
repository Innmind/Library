<?php
declare(strict_types = 1);

namespace Domain\Specification\Domain;

use Domain\Entity\{
    Domain,
    Domain\TopLevelDomain as Model
};
use Innmind\Specification\ComparatorInterface;

final class TopLevelDomain implements ComparatorInterface, Specification
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

    public function isSatisfiedBy(Domain $domain): bool
    {
        return (string) $domain->tld() === $this->value;
    }
}
