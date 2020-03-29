<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Repository\HostRepository,
    Repository\DomainHostRepository,
    Command\RegisterHost,
    Specification\Host\Name,
    Entity\Host,
    Entity\Host\Name as Model,
    Entity\DomainHost,
    Exception\HostAlreadyExist
};
use Innmind\TimeContinuum\TimeContinuumInterface;

final class RegisterHostHandler
{
    private HostRepository $hostRepository;
    private DomainHostRepository $domainHostRepository;
    private TimeContinuumInterface $clock;

    public function __construct(
        HostRepository $hostRepository,
        DomainHostRepository $domainHostRepository,
        TimeContinuumInterface $clock
    ) {
        $this->hostRepository = $hostRepository;
        $this->domainHostRepository = $domainHostRepository;
        $this->clock = $clock;
    }

    public function __invoke(RegisterHost $wished): void
    {
        $name = new Model((string) $wished->host());
        $hosts = $this->hostRepository->matching(
            new Name($name)
        );

        if ($hosts->size() > 0) {
            throw new HostAlreadyExist($hosts->current());
        }

        $host = Host::register(
            $wished->identity(),
            $name
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
