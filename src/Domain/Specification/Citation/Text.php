<?php
declare(strict_types = 1);

namespace Domain\Specification\Citation;

use Domain\Specification\Composable;
use Innmind\Specification\ComparatorInterface;

final class Text implements ComparatorInterface
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
        return 'text';
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
