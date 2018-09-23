<?php
declare(strict_types = 1);

namespace Domain\Specification\Alternate;

use Domain\{
    Entity\Alternate as Entity,
    Model\Language as Model
};
use Innmind\Specification\ComparatorInterface;

final class Language implements ComparatorInterface, Specification
{
    use Composable;

    private $value;

    public function __construct(Model $value)
    {
        $this->value = (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function property(): string
    {
        return 'language';
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
        return (string) $alternate->language() === $this->value;
    }
}
