<?php
declare(strict_types = 1);

namespace Domain\Specification\Reference;

use Domain\Entity\{
    Reference,
    HttpResource\IdentityInterface
};
use Innmind\Specification\ComparatorInterface;

final class Source implements ComparatorInterface, SpecificationInterface
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
        return 'source';
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

    public function isSatisfiedBy(Reference $reference): bool
    {
        return (string) $reference->source() === $this->value;
    }
}
