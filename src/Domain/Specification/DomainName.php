<?php
declare(strict_types = 1);

namespace Domain\Specification;

use Innmind\Specification\ComparatorInterface;

final class DomainName implements ComparatorInterface
{
    use Composable;

    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function property(): string
    {
        return 'name';
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
