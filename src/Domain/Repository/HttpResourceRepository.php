<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\HttpResource\Identity,
    Entity\HttpResource,
    Specification\HttpResource\Specification
};
use Innmind\Immutable\SetInterface;

interface HttpResourceRepository
{
    /**
     * @throws HttpResourceNotFoundException
     */
    public function get(Identity $identity): HttpResource;
    public function add(HttpResource $httpResource): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<HttpResource>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<HttpResource>
     */
    public function matching(Specification $specification): SetInterface;
}
