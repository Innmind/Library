<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Repository\HostRepositoryInterface,
    Repository\DomainHostRepositoryInterface,
    Command\RegisterHost,
    Specification\Host\Name,
    Entity\Host,
    Entity\DomainHost,
    Exception\HostAlreadyExistException
};
use Innmind\TimeContinuum\TimeContinuumInterface;

final class RegisterHostHandler
{
    private $hostRepository;
    private $domainHostRepository;
    private $clock;

    public function __construct(
        HostRepositoryInterface $hostRepository,
        DomainHostRepositoryInterface $domainHostRepository,
        TimeContinuumInterface $clock
    ) {
        $this->hostRepository = $hostRepository;
        $this->domainHostRepository = $domainHostRepository;
        $this->clock = $clock;
    }

    public function __invoke(RegisterHost $wished): void
    {
        $hosts = $this->hostRepository->matching(
            new Name((string) $wished->host())
        );

        if ($hosts->size() > 0) {
            throw new HostAlreadyExistException;
        }

        $host = Host::register(
            $wished->identity(),
            (string) $wished->host()
        );
        $relation = DomainHost::create(
            $wished->relation(),
            $wished->domain(),
            $wished->identity(),
            $this->clock->now()
        );

        $this->hostRepository->add($host);
        $this->domainHostRepository->add($relation);
    }
}
