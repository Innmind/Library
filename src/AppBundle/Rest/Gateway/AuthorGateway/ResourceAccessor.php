<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\AuthorGateway;

use AppBundle\Entity\Author\Identity;
use Domain\Repository\AuthorRepositoryInterface;
use Innmind\Rest\Server\{
    ResourceAccessorInterface,
    IdentityInterface,
    HttpResourceInterface,
    HttpResource,
    Property,
    Definition\HttpResource as ResourceDefinition
};
use Innmind\Immutable\Map;

final class ResourceAccessor implements ResourceAccessorInterface
{
    private $repository;

    public function __construct(AuthorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(
        ResourceDefinition $definition,
        IdentityInterface $identity
    ): HttpResourceInterface {
        $author = $this->repository->get(
            new Identity((string) $identity)
        );

        return new HttpResource(
            $definition,
            (new Map('string', Property::class))
                ->put(
                    'identity',
                    new Property('identity', (string) $author->identity())
                )
                ->put(
                    'name',
                    new Property('name', (string) $author->name())
                )
        );
    }
}
