<?php
declare(strict_types = 1);

namespace Domain\Specification\Alternate;

use Domain\{
    Entity\HttpResource\Identity,
    Entity\Alternate as Entity,
};
use Innmind\Specification\{
    Comparator,
    Sign,
};

final class HttpResource implements Comparator, Specification
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
        return 'resource';
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

    public function isSatisfiedBy(Entity $alternate): bool
    {
        return $alternate->resource()->toString() === $this->value;
    }
}
