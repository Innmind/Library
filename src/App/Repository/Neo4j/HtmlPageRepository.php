<?php
declare(strict_types = 1);

namespace App\Repository\Neo4j;

use Domain\{
    Repository\HtmlPageRepository as HtmlPageRepositoryInterface,
    Entity\HtmlPage,
    Entity\HtmlPage\Identity,
    Exception\HtmlPageNotFound,
    Specification\HttpResource\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\Set;

final class HtmlPageRepository implements HtmlPageRepositoryInterface
{
    private Repository $infrastructure;

    public function __construct(Repository $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identity $identity): HtmlPage
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFound $e) {
            throw new HtmlPageNotFound('', 0, $e);
        }
    }

    public function add(HtmlPage $page): HtmlPageRepositoryInterface
    {
        $this->infrastructure->add($page);

        return $this;
    }

    public function remove(Identity $identity): HtmlPageRepositoryInterface
    {
        $this->infrastructure->remove(
            $this->get($identity)
        );

        return $this;
    }

    public function has(Identity $identity): bool
    {
        return $this->infrastructure->contains($identity);
    }

    public function count(): int
    {
        return $this->infrastructure->all()->size();
    }

    /**
     * {@inheritdoc}
     */
    public function all(): Set
    {
        return $this
            ->infrastructure
            ->all()
            ->reduce(
                Set::of(HtmlPage::class),
                function(Set $all, HtmlPage $page): Set {
                    return $all->add($page);
                }
            );
    }

    /**
     * {@inheritdoc}
     */
    public function matching(Specification $specification): Set
    {
        return $this
            ->infrastructure
            ->matching($specification)
            ->reduce(
                Set::of(HtmlPage::class),
                function(Set $all, HtmlPage $page): Set {
                    return $all->add($page);
                }
            );
    }
}
