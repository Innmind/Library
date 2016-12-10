<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\RegisterDomain,
    Entity\Domain,
    Entity\Domain\Name,
    Repository\DomainRepositoryInterface,
    Specification\Domain\Name as NameSpec,
    Specification\Domain\TopLevelDomain,
    Exception\DomainAlreadyExistException
};
use Pdp\Parser;

final class RegisterDomainHandler
{
    private $repository;
    private $parser;

    public function __construct(
        DomainRepositoryInterface $repository,
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

        $existing = $this->repository->matching(
            (new NameSpec($name))
                ->and(new TopLevelDomain($tld))
        );

        if ($existing->size() !== 0) {
            throw new DomainAlreadyExistException;
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
