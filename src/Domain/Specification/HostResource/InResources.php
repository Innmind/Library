<?php
declare(strict_types = 1);

namespace Domain\Specification\HostResource;

use Domain\{
    Specification\Composable,
    Entity\HttpResource\IdentityInterface,
    Exception\InvalidArgumentException
};
use Innmind\Specification\ComparatorInterface;
use Innmind\Immutable\SetInterface;

final class InResources implements ComparatorInterface
{
    use Composable;

    private $value;

    public function __construct(SetInterface $value)
    {
        if ((string) $value->type() !== IdentityInterface::class) {
            throw new InvalidArgumentException;
        }

        $this->value = $value->reduce(
            [],
            function(array $value, IdentityInterface $identity): array {
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
}
