<?php
declare(strict_types = 1);

namespace Domain\Specification\Reference;

use Domain\Entity\{
    Reference,
    HttpResource\Identity,
};
use Innmind\Specification\{
    Comparator,
    Sign,
};

final class Target implements Comparator, Specification
{
    use Composable;

    private string $value;

    public function __construct(Identity $value)
    {
        $this->value = $value->toString();
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

    public function isSatisfiedBy(Reference $reference): bool
    {
        return $reference->target()->toString() === $this->value;
    }
}
