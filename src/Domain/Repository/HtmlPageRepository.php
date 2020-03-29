<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\{
    Entity\HtmlPage\Identity,
    Entity\HtmlPage,
    Specification\HttpResource\Specification
};
use Innmind\Immutable\Set;

interface HtmlPageRepository
{
    /**
     * @throws HtmlPageNotFoundException
     */
    public function get(Identity $identity): HtmlPage;
    public function add(HtmlPage $htmlPage): self;
    public function remove(Identity $identity): self;
    public function has(Identity $identity): bool;
    public function count(): int;

    /**
     * @return Set<HtmlPage>
     */
    public function all(): Set;

    /**
     * @return Set<HtmlPage>
     */
    public function matching(Specification $specification): Set;
}
