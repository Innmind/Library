<?php
declare(strict_types = 1);

namespace Domain\Specification\HostResource;

use Domain\Entity\{
    HostResource,
    Host\Identity,
};
use Innmind\Specification\{
    Comparator,
    Sign,
};

final class Host implements Comparator, Specification
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
        return 'host';
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

    public function isSatisfiedBy(HostResource $relation): bool
    {
        return $relation->host()->toString() === $this->value;
    }
}
