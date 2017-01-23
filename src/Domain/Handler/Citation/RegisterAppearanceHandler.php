<?php
declare(strict_types = 1);

namespace Domain\Handler\Citation;

use Domain\{
    Command\Citation\RegisterAppearance,
    Repository\CitationAppearanceRepositoryInterface,
    Entity\CitationAppearance,
    Specification\CitationAppearance\Citation,
    Specification\CitationAppearance\HttpResource,
    Exception\CitationAppearanceAlreadyExistException
};
use Innmind\TimeContinuum\TimeContinuumInterface;

final class RegisterAppearanceHandler
{
    private $repository;
    private $clock;

    public function __construct(
        CitationAppearanceRepositoryInterface $repository,
        TimeContinuumInterface $clock
    ) {
        $this->repository = $repository;
        $this->clock = $clock;
    }

    public function __invoke(RegisterAppearance $wished): void
    {
        $appearances = $this->repository->matching(
            (new Citation($wished->citation()))
                ->and(new HttpResource($wished->resource()))
        );

        if ($appearances->size() > 0) {
            throw new CitationAppearanceAlreadyExistException(
                $appearances->current()
            );
        }

        $this->repository->add(
            CitationAppearance::register(
                $wished->identity(),
                $wished->citation(),
                $wished->resource(),
                $this->clock->now()
            )
        );
    }
}
