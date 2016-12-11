<?php
declare(strict_types = 1);

namespace Domain\Specification\Alternate;

use Domain\{
    Entity\HttpResource\IdentityInterface,
    Entity\Alternate
};
use Innmind\Specification\ComparatorInterface;

final class HttpResource implements ComparatorInterface, SpecificationInterface
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
        return 'resource';
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

    public function isSatisfiedBy(Alternate $alternate): bool
    {
        return (string) $alternate->resource() === $this->value;
    }
}
