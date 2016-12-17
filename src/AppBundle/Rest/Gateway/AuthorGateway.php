<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway;

use AppBundle\Rest\Gateway\AuthorGateway\ResourceCreator;
use Innmind\Rest\Server\{
    GatewayInterface,
    ResourceListAccessorInterface,
    ResourceAccessorInterface,
    ResourceCreatorInterface,
    ResourceUpdaterInterface,
    ResourceRemoverInterface,
    ResourceLinkerInterface,
    ResourceUnlinkerInterface,
    Exception\ActionNotImplementedException
};

final class AuthorGateway implements GatewayInterface
{
    private $resourceCreator;

    public function __construct(ResourceCreator $resourceCreator)
    {
        $this->resourceCreator = $resourceCreator;
    }

    public function resourceListAccessor(): ResourceListAccessorInterface
    {
        throw new ActionNotImplementedException;
    }

    public function resourceAccessor(): ResourceAccessorInterface
    {
        throw new ActionNotImplementedException;
    }
    public function resourceCreator(): ResourceCreatorInterface
    {
        return $this->resourceCreator;
    }

    public function resourceUpdater(): ResourceUpdaterInterface
    {
        throw new ActionNotImplementedException;
    }

    public function resourceRemover(): ResourceRemoverInterface
    {
        throw new ActionNotImplementedException;
    }

    public function resourceLinker(): ResourceLinkerInterface
    {
        throw new ActionNotImplementedException;
    }

    public function resourceUnlinker(): ResourceUnlinkerInterface
    {
        throw new ActionNotImplementedException;
    }
}
