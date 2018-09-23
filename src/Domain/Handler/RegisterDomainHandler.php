<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\RegisterDomain,
    Entity\Domain,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain,
    Repository\DomainRepository,
    Specification\Domain\Name as NameSpec,
    Specification\Domain\TopLevelDomain as TopLevelDomainSpec,
    Exception\DomainAlreadyExist
};
use Pdp\Rules;

final class RegisterDomainHandler
{
    private $repository;
    private $rules;

    public function __construct(
        DomainRepository $repository,
        Rules $rules
    ) {
        $this->repository = $repository;
        $this->rules = $rules;
    }

    public function __invoke(RegisterDomain $wished): void
    {
        $domain = $this->rules->resolve((string) $wished->host());
        [$name, $tld] = explode(
            '.',
            (string) $domain->getRegistrableDomain(),
            2
        );
        $name = new Name($name);
        $tld = new TopLevelDomain($tld);

        $existing = $this->repository->matching(
            (new NameSpec($name))
                ->and(new TopLevelDomainSpec($tld))
        );

        if ($existing->size() !== 0) {
            throw new DomainAlreadyExist($existing->current());
        }

        $this->repository->add(
            Domain::register(
                $wished->identity(),
                $name,
                $tld
            )
        );
    }
}
