<?php
declare(strict_types = 1);

namespace Domain\Specification\Canonical;

use Domain\Entity\{
    Canonical as Entity,
    HttpResource\IdentityInterface
};
use Innmind\Specification\ComparatorInterface;

final class Canonical implements ComparatorInterface, SpecificationInterface
{
    use Composable;

    private $value;

    public function __construct(IdentityInterface $value)
    {
        $this->value = (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function property(): string
    {
        return 'canonical';
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

    public function isSatisfiedBy(Entity $canonical): bool
    {
        return (string) $canonical->canonical() === $this->value;
    }
}
