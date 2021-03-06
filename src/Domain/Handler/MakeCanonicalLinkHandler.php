<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\MakeCanonicalLink,
    Repository\CanonicalRepository,
    Entity\Canonical,
    Specification\Canonical\HttpResource,
    Specification\Canonical\Canonical as CanonicalSpec,
    Exception\CanonicalAlreadyExist
};
use Innmind\TimeContinuum\Clock;
use function Innmind\Immutable\first;

final class MakeCanonicalLinkHandler
{
    private CanonicalRepository $repository;
    private Clock $clock;

    public function __construct(
        CanonicalRepository $repository,
        Clock $clock
    ) {
        $this->repository = $repository;
        $this->clock = $clock;
    }

    public function __invoke(MakeCanonicalLink $wished): void
    {
        /** @psalm-suppress InvalidArgument */
        $canonicals = $this->repository->matching(
            (new HttpResource($wished->resource()))
                ->and(new CanonicalSpec($wished->canonical()))
        );

        if ($canonicals->size() > 0) {
            throw new CanonicalAlreadyExist(first($canonicals));
        }

        $this->repository->add(
            Canonical::create(
                $wished->identity(),
                $wished->canonical(),
                $wished->resource(),
                $this->clock->now()
            )
        );
    }
}
