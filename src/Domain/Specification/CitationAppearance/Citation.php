<?php
declare(strict_types = 1);

namespace Domain\Specification\CitationAppearance;

use Domain\{
    Specification\Composable,
    Entity\Citation\IdentityInterface
};
use Innmind\Specification\ComparatorInterface;

final class Citation implements ComparatorInterface
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
        return 'citation';
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
