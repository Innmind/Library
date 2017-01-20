<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\DomainGateway;

use AppBundle\Entity\Domain\Identity;
use Domain\Repository\DomainRepositoryInterface;
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

    public function __construct(DomainRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(
        ResourceDefinition $definition,
        IdentityInterface $identity
    ): HttpResourceInterface {
        $domain = $this->repository->get(
            new Identity((string) $identity)
        );

        return new HttpResource(
            $definition,
            (new Map('string', Property::class))
                ->put(
                    'identity',
                    new Property('identity', (string) $domain->identity())
                )
                ->put(
                    'name',
                    new Property('name', (string) $domain->name())
                )
                ->put(
                    'tld',
                    new Property('tld', (string) $domain->tld())
                )
        );
    }
}
