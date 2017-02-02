<?php
declare(strict_types = 1);

namespace AppBundle\Repository\Neo4j;

use Domain\{
    Repository\HtmlPageRepositoryInterface,
    Entity\HtmlPage,
    Entity\HtmlPage\IdentityInterface,
    Exception\HtmlPageNotFoundException,
    Specification\HttpResource\SpecificationInterface
};
use Innmind\Neo4j\ONM\{
    RepositoryInterface,
    Exception\EntityNotFoundException
};
use Innmind\Immutable\{
    SetInterface,
    Set
};

final class HtmlPageRepository implements HtmlPageRepositoryInterface
{
    private $infrastructure;

    public function __construct(RepositoryInterface $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdentityInterface $identity): HtmlPage
    {
        try {
            return $this->infrastructure->get($identity);
        } catch (EntityNotFoundException $e) {
            throw new HtmlPageNotFoundException('', 0, $e);
        }
    }

    public function add(HtmlPage $page): HtmlPageRepositoryInterface
    {
        $this->infrastructure->add($page);

        return $this;
    }

    public function remove(IdentityInterface $identity): HtmlPageRepositoryInterface
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
                new Set(HtmlPage::class),
                function(Set $all, HtmlPage $page): Set {
                    return $all->add($page);
                }
            );
    }

    /**
     * {@inheritdoc}
     */
    public function matching(SpecificationInterface $specification): SetInterface
    {
        return $this
            ->infrastructure
            ->matching($specification)
            ->reduce(
                new Set(HtmlPage::class),
                function(Set $all, HtmlPage $page): Set {
                    return $all->add($page);
                }
            );
    }
}
