<?php
declare(strict_types = 1);

namespace Domain\Specification\Alternate;

use Domain\Entity\{
    HttpResource\Identity,
    Alternate as Entity
};
use Innmind\Specification\ComparatorInterface;

final class Alternate implements ComparatorInterface, Specification
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
        return 'alternate';
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

    public function isSatisfiedBy(Entity $alternate): bool
    {
        return (string) $alternate->alternate() === $this->value;
    }
}
