<?php
declare(strict_types = 1);

namespace Domain\Repository;

use Domain\Entity\{
    HtmlPage\IdentityInterface,
    HtmlPage
};
use Innmind\Immutable\SetInterface;
use Innmind\Specification\SpecificationInterface;

interface HtmlPageRepositoryInterface
{
    public function get(IdentityInterface $identity): HtmlPage;
    public function add(HtmlPage $htmlPage): self;
    public function remove(IdentityInterface $identity): self;
    public function has(IdentityInterface $identity): bool;
    public function count(): int;

    /**
     * @return SetInterface<HtmlPage>
     */
    public function all(): SetInterface;

    /**
     * @return SetInterface<HtmlPage>
     */
    public function matching(SpecificationInterface $specification): SetInterface;
}
