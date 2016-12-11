<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\HttpResource\IdentityInterface,
    Entity\HttpResource,
    Specification\HttpResource\SpecificationInterface
};
use Innmind\Immutable\SetInterface;

interface HttpResourceRepositoryInterface
{
    public function get(IdentityInterface $identity): HttpResource;
    public function add(HttpResource $httpResource): self;
    public function remove(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<HttpResource>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<HttpResource>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
