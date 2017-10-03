<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Image\Identity,
    Entity\Image,
    Specification\HttpResource\Specification
};
use Innmind\Immutable\SetInterface;

interface ImageRepository
{
    /**
     * @throws ImageNotFoundException
     */
    public function get(Identity $identity): Image;
    public function add(Image $image): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<Image>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<Image>
     */
    public function matching(Specification $specification): SetInterface;
}
