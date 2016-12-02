<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\RegisterCitationAppearance,
    Repository\CitationAppearanceRepositoryInterface,
    Entity\CitationAppearance,
    Specification\CitationAppearance\Citation,
    Specification\CitationAppearance\HttpResource,
    Exception\CitationAppearanceAlreadyExistException
};
use Innmind\TimeContinuum\TimeContinuumInterface;

final class RegisterCitationAppearanceHandler
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

    public function __invoke(RegisterCitationAppearance $wished): void
    {
        $appearances = $this->repository->matching(
            (new Citation($wished->citation()))
                ->and(new HttpResource($wished->resource()))
        );

        if ($appearances->size() > 0) {
            throw new CitationAppearanceAlreadyExistException;
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
