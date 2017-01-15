<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\ImageRepositoryInterface,
    Entity\Image,
    Entity\Image\IdentityInterface,
    Specification\HttpResource\SpecificationInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class ImageRepository implements ImageRepositoryInterface
{
    private $infrastructure;

    public function __construct(RepositoryInterface $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    public function get(IdentityInterface $identity): Image
    {
        return $this->infrastructure->get($identity);
    }

    public function add(Image $image): ImageRepositoryInterface
    {
        $this->infrastructure->add($image);

        return $this;
    }

    public function remove(IdentityInterface $identity): ImageRepositoryInterface
    {
        $this->infrastructure->remove(
            $this->get($identity)
        );

        return $this;
    }

    public function has(IdentityInterface $identity): bool
    {
        return $this->infrastructure->has($identity);
    }

    public function count(): int
    {
        return $this->infrastructure->all()->size();
    }

    /**
     * {@inheritdoc}
     */
    public function all(): SetInterface
    {
        return $this
            ->infrastructure
            ->all()
            ->reduce(
                new Set(Image::class),
                function(Set $all, Image $image): Set {
                    return $all->add($image);
                }
            );
    }

    /**
     * @return SetInterface<Image>
     */
    public function matching(SpecificationInterface $specification): SetInterface
    {
        return $this
            ->infrastructure
            ->matching($specification)
            ->reduce(
                new Set(Image::class),
                function(Set $all, Image $image): Set {
                    return $all->add($image);
                }
            );
    }
}
