<?php
declare(strict_types = 1);

namespace Domain\Specification\HostResource;

use Domain\{
    Entity\HostResource,
    Entity\HttpResource\Identity,
    Exception\InvalidArgumentException,
};
use Innmind\Specification\{
    Comparator,
    Sign,
};
use Innmind\Immutable\Set;
use function Innmind\Immutable\{
    unwrap,
    assertSet,
};

final class InResources implements Comparator, Specification
{
    use Composable;

    /** @var Set<string> */
    private Set $value;

    /**
     * @param Set<Identity> $value
     */
    public function __construct(Set $value)
    {
        assertSet(Identity::class, $value, 1);

        /** @var Set<string> */
        $this->value = $value->reduce(
            Set::of('string'),
            static function(Set $carry, Identity $identity): Set {
                return $carry->add($identity->toString());
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
    public function sign(): Sign
    {
        return Sign::in();
    }

    /**
     * {@inheritdoc}
     */
    public function value()
    {
        return unwrap($this->value);
    }

    public function isSatisfiedBy(HostResource $relation): bool
    {
        return $this->value->contains($relation->resource()->toString());
    }
}
