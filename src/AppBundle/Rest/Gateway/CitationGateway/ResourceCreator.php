<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\CitationGateway;

use AppBundle\Entity\Citation\Identity;
use Domain\{
    Command\RegisterCitation,
    Entity\Citation\Text
};
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
            new RegisterCitation(
                $identity = new Identity((string) Uuid::uuid4()),
                new Text($resource->property('text')->value())
            )
        );

        return $identity;
    }
}
