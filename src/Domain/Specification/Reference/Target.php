<?php
declare(strict_types = 1);

namespace Domain\Specification\Reference;

use Domain\Entity\{
    Reference,
    HttpResource\Identity
};
use Innmind\Specification\ComparatorInterface;

final class Target implements ComparatorInterface, Specification
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
        return 'target';
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

    public function isSatisfiedBy(Reference $reference): bool
    {
        return (string) $reference->target() === $this->value;
    }
}
