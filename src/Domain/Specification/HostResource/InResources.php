<?php
declare(strict_types = 1);

namespace Domain\Specification\HostResource;

use Domain\{
    Entity\HostResource,
    Entity\HttpResource\Identity,
    Exception\InvalidArgumentException
};
use Innmind\Specification\ComparatorInterface;
use Innmind\Immutable\SetInterface;

final class InResources implements ComparatorInterface, Specification
{
    use Composable;

    private $value;

    public function __construct(SetInterface $value)
    {
        if ((string) $value->type() !== Identity::class) {
            throw new InvalidArgumentException;
        }

        $this->value = $value->reduce(
            [],
            function(array $value, Identity $identity): array {
                $value[] = (string) $identity;

                return $value;
            }
        );
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
        return 'in';
    }

    /**
     * {@inheritdoc}
     */
    public function value()
    {
        return $this->value;
    }

    public function isSatisfiedBy(HostResource $relation): bool
    {
        return in_array((string) $relation->resource(), $this->value, true);
    }
}
