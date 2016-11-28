<?php
declare(strict_types = 1);

namespace Domain\Specification\HttpResource;

use Domain\Specification\Composable;
use Innmind\Specification\ComparatorInterface;
use Innmind\Url\QueryInterface;

final class Query implements ComparatorInterface
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
}
