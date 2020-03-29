<?php
declare(strict_types = 1);

namespace Domain\Specification\HttpResource;

use Domain\Entity\HttpResource;
use Innmind\Specification\{
    Comparator,
    Sign,
};
use Innmind\Url\QueryInterface;

final class Query implements Comparator, Specification
{
    use Composable;

    private string $value;

    public function __construct(QueryInterface $value)
    {
        $this->value = (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function property(): string
    {
        return 'query';
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

    public function isSatisfiedBy(HttpResource $resource): bool
    {
        return (string) $resource->query() === $this->value;
    }
}
