<?php
declare(strict_types = 1);

namespace Domain\Specification\HttpResource;

use Domain\Entity\HttpResource;
use Innmind\Specification\ComparatorInterface;
use Innmind\Url\QueryInterface;

final class Query implements ComparatorInterface, Specification
{
    use Composable;

    private $value;

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

    public function isSatisfiedBy(HttpResource $resource): bool
    {
        return (string) $resource->query() === $this->value;
    }
}
