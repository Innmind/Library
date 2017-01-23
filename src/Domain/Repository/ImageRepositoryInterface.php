<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Image\IdentityInterface,
    Entity\Image,
    Specification\HttpResource\SpecificationInterface
};
use Innmind\Immutable\SetInterface;

interface ImageRepositoryInterface
{
    public function get(IdentityInterface $identity): Image;
    public function add(Image $image): self;
    public function remove(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Image>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Image>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
