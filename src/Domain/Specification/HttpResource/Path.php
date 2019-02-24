<?php
declare(strict_types = 1);

namespace Domain\Specification\HttpResource;

use Domain\Entity\HttpResource;
use Innmind\Specification\{
    Comparator,
    Sign,
};
use Innmind\Url\PathInterface;

final class Path implements Comparator, Specification
{
    use Composable;

    private $value;

    public function __construct(PathInterface $value)
    {
        $this->value = (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function property(): string
    {
        return 'path';
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
        return (string) $resource->path() === $this->value;
    }
}
