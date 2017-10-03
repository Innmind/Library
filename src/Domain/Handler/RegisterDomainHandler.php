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
    Exception\DomainAlreadyExistException
};
use Pdp\Parser;

final class RegisterDomainHandler
{
    private $repository;
    private $parser;

    public function __construct(
        DomainRepository $repository,
        Parser $parser
    ) {
        $this->repository = $repository;
        $this->parser = $parser;
    }

    public function __invoke(RegisterDomain $wished): void
    {
        $parsed = $this->parser->parseUrl((string) $wished->host());
        [$name, $tld] = explode(
            '.',
            (string) $parsed->host->registerableDomain,
            2
        );
        $name = new Name($name);
        $tld = new TopLevelDomain($tld);

        $existing = $this->repository->matching(
            (new NameSpec($name))
                ->and(new TopLevelDomainSpec($tld))
        );

        if ($existing->size() !== 0) {
            throw new DomainAlreadyExistException($existing->current());
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
