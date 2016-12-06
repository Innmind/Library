<?php
declare(strict_types = 1);

namespace Domain\Specification\Alternate;

use Domain\{
    Specification\Composable,
    Entity\HttpResource\IdentityInterface
};
use Innmind\Specification\ComparatorInterface;

final class HttpResource implements ComparatorInterface
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
}
