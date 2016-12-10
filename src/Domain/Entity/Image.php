<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Image\IdentityInterface,
    Entity\Image\Weight,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\ImageRegistered,
    Event\Image\DimensionSpecified,
    Event\Image\WeightSpecified,
    Event\Image\DescriptionAdded,
    Model\Image\Dimension,
    Exception\InvalidArgumentException
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Immutable\{
    Set,
    SetInterface
};

final class Image extends HttpResource
{
    private $dimension;
    private $weight;
    private $descriptions;

    public function __construct(
        ResourceIdentity $identity,
        PathInterface $path,
        QueryInterface $query
    ) {
        if (!$identity instanceof IdentityInterface) {
            throw new InvalidArgumentException;
        }

        parent::__construct($identity, $path, $query);
        $this->descriptions = new Set('string');
    }

    public static function register(
        ResourceIdentity $identity,
        PathInterface $path,
        QueryInterface $query
    ): HttpResource {
        $self = new self($identity, $path, $query);
        $self->record(new ImageRegistered($identity, $path, $query));

        return $self;
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

    public function dimension(): Dimension
    {
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

    public function weight(): Weight
    {
        return $this->weight;
    }

    public function addDescription(string $description): self
    {
        if (empty($description)) {
            throw new InvalidArgumentException;
        }

        $descriptions = $this->descriptions;
        $this->descriptions = $this->descriptions->add($description);

        if ($this->descriptions !== $descriptions) {
            $this->record(new DescriptionAdded($this->identity(), $description));
        }

        return $this;
    }

    public function descriptions(): SetInterface
    {
        return $this->descriptions;
    }
}
