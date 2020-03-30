<?php
declare(strict_types = 1);

namespace Domain\Specification\HttpResource;

use Domain\Entity\HttpResource;
use Innmind\Specification\{
    Comparator,
    Sign,
};
use Innmind\Url\Path as PathModel;

final class Path implements Comparator, Specification
{
    use Composable;

    private string $value;

    public function __construct(PathModel $value)
    {
        $this->value = $value->toString();
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
        return $resource->path()->toString() === $this->value;
    }
}
