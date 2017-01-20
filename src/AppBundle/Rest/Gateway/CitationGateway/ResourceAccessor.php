<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\CitationGateway;

use AppBundle\Entity\Citation\Identity;
use Domain\Repository\CitationRepositoryInterface;
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

    public function __construct(CitationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(
        ResourceDefinition $definition,
        IdentityInterface $identity
    ): HttpResourceInterface {
        $citation = $this->repository->get(
            new Identity((string) $identity)
        );

        return new HttpResource(
            $definition,
            (new Map('string', Property::class))
                ->put(
                    'identity',
                    new Property('identity', (string) $citation->identity())
                )
                ->put(
                    'text',
                    new Property('text', (string) $citation->text())
                )
        );
    }
}
