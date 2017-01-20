<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\DomainGateway;

use AppBundle\Entity\Domain\Identity;
use Domain\Command\RegisterDomain;
use Innmind\Url\Authority\Host;
use Innmind\Rest\Server\{
    ResourceCreatorInterface,
    Definition\HttpResource as ResourceDefinition,
    HttpResourceInterface,
    IdentityInterface
};
use Innmind\CommandBus\CommandBusInterface;
use Ramsey\Uuid\Uuid;

final class ResourceCreator implements ResourceCreatorInterface
{
    private $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(
        ResourceDefinition $definition,
        HttpResourceInterface $resource
    ): IdentityInterface {
        $this->commandBus->handle(
            new RegisterDomain(
                $identity = new Identity((string) Uuid::uuid4()),
                new Host($resource->property('authority')->value())
            )
        );

        return $identity;
    }
}
