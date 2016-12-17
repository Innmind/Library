<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\AuthorGateway;

use AppBundle\Entity\Author\Identity;
use Domain\{
    Command\RegisterAuthor,
    Entity\Author\Name
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
            new RegisterAuthor(
                $identity = new Identity((string) Uuid::uuid4()),
                new Name($resource->property('name')->value())
            )
        );

        return $identity;
    }
}
