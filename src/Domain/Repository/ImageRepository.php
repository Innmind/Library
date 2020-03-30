<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\Image\Identity,
    Entity\Image,
    Specification\HttpResource\Specification,
    Exception\ImageNotFound,
};
use Innmind\Immutable\Set;

interface ImageRepository
{
    /**
     * @throws ImageNotFound
     */
    public function get(Identity $identity): Image;
    public function add(Image $image): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return Set<Image>
     */
    public function all(): Set;

    /**
     * @return Set<Image>
     */
    public function matching(Specification $specification): Set;
}
