<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway;

use AppBundle\Rest\Gateway\HttpResourceGateway\{
    ResourceCreator,
    ResourceAccessor
};
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

final class HttpResourceGateway implements GatewayInterface
{
    private $resourceCreator;
    private $resourceAccessor;

    public function __construct(
        ResourceCreator $resourceCreator,
        ResourceAccessor $resourceAccessor
    ) {
        $this->resourceCreator = $resourceCreator;
        $this->resourceAccessor = $resourceAccessor;
    }

    public function resourceListAccessor(): ResourceListAccessorInterface
    {
        throw new ActionNotImplementedException;
    }

    public function resourceAccessor(): ResourceAccessorInterface
    {
        return $this->resourceAccessor;
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
