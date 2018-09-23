<?php
declare(strict_types = 1);

namespace Domain\Specification\HostResource;

use Domain\Entity\{
    HostResource,
    Host\Identity
};
use Innmind\Specification\ComparatorInterface;

final class Host implements ComparatorInterface, Specification
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
        return 'host';
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

    public function isSatisfiedBy(HostResource $relation): bool
    {
        return (string) $relation->host() === $this->value;
    }
}
