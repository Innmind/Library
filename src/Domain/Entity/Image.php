<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Image\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\ImageRegistered,
    Event\Image\DimensionSpecified,
    Event\Image\WeightSpecified,
    Model\Image\Dimension,
    Exception\InvalidArgumentException
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

final class Image extends HttpResource
{
    private $dimension;
    private $weight;

    public function __construct(
        ResourceIdentity $identity,
        PathInterface $path,
        QueryInterface $query
    ) {
        if (!$identity instanceof IdentityInterface) {
            throw new InvalidArgumentException;
        }

        parent::__construct($identity, $path, $query);
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

    public function specifyWeight(int $weight): self
    {
        if ($weight < 0) {
            throw new InvalidArgumentException;
        }

        $this->weight = $weight;
        $this->record(new WeightSpecified($this->identity(), $weight));

        return $this;
    }

    public function isWeightKnown(): bool
    {
        return is_int($this->weight);
    }

    public function weight(): int
    {
        return $this->weight;
    }
}
