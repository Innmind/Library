<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Image\Identity,
    Entity\Image\Description,
    Entity\Image\Weight,
    Entity\Image\Dimension,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\ImageRegistered,
    Event\Image\DimensionSpecified,
    Event\Image\WeightSpecified,
    Event\Image\DescriptionAdded,
    Exception\InvalidArgumentException,
};
use Innmind\Url\{
    Path,
    Query,
};
use Innmind\Immutable\Set;

final class Image extends HttpResource
{
    private ?Dimension $dimension = null;
    private ?Weight $weight = null;
    /** @var Set<Description> */
    private Set $descriptions;

    public function __construct(
        ResourceIdentity $identity,
        Path $path,
        Query $query
    ) {
        if (!$identity instanceof Identity) {
            throw new InvalidArgumentException;
        }

        parent::__construct($identity, $path, $query);
        /** @var Set<Description> */
        $this->descriptions = Set::of(Description::class);
    }

    public static function register(
        ResourceIdentity $identity,
        Path $path,
        Query $query
    ): self {
        $self = new self($identity, $path, $query);
        /** @psalm-suppress ArgumentTypeCoercion */
        $self->record(new ImageRegistered($identity, $path, $query));

        return $self;
    }

    /** @psalm-suppress MoreSpecificReturnType */
    public function identity(): Identity
    {
        /** @psalm-suppress LessSpecificReturnStatement */
        return parent::identity();
    }

    public function specifyDimension(Dimension $dimension): self
    {
        $this->dimension = $dimension;
        $this->record(new DimensionSpecified($this->identity(), $dimension));

        return $this;
    }

    public function isDimensionKnown(): bool
    {
        return $this->dimension instanceof Dimension;
    }

    /** @psalm-suppress InvalidNullableReturnType */
    public function dimension(): Dimension
    {
        /** @psalm-suppress NullableReturnStatement */
        return $this->dimension;
    }

    public function specifyWeight(Weight $weight): self
    {
        $this->weight = $weight;
        $this->record(new WeightSpecified($this->identity(), $weight));

        return $this;
    }

    public function isWeightKnown(): bool
    {
        return $this->weight instanceof Weight;
    }

    /** @psalm-suppress InvalidNullableReturnType */
    public function weight(): Weight
    {
        /** @psalm-suppress NullableReturnStatement */
        return $this->weight;
    }

    public function addDescription(Description $description): self
    {
        $known = $this
            ->descriptions
            ->reduce(
                false,
                function(bool $known, Description $inSet) use ($description): bool {
                    return $known ?: $description->equals($inSet);
                }
            );

        if (!$known) {
            $this->descriptions = $this->descriptions->add($description);
            $this->record(new DescriptionAdded($this->identity(), $description));
        }

        return $this;
    }

    /**
     * @return Set<Description>
     */
    public function descriptions(): Set
    {
        return $this->descriptions;
    }
}
