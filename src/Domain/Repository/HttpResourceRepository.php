<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\HttpResource\Identity,
    Entity\HttpResource,
    Specification\HttpResource\Specification
};
use Innmind\Immutable\Set;

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
     * @return Set<HttpResource>
     */
    public function all(): Set;

    /**
     * @return Set<HttpResource>
     */
    public function matching(Specification $specification): Set;
}
