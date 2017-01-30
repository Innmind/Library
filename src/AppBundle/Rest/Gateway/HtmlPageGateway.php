<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway;

use AppBundle\Rest\Gateway\HtmlPageGateway\{
    ResourceCreator,
    ResourceAccessor,
    ResourceLinker
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

final class HtmlPageGateway implements GatewayInterface
{
    private $resourceCreator;
    private $resourceAccessor;
    private $resourceLinker;

    public function __construct(
        ResourceCreator $resourceCreator,
        ResourceAccessor $resourceAccessor,
        ResourceLinker $resourceLinker
    ) {
        $this->resourceCreator = $resourceCreator;
        $this->resourceAccessor = $resourceAccessor;
        $this->resourceLinker = $resourceLinker;
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
        return $this->resourceLinker;
    }

    public function resourceUnlinker(): ResourceUnlinkerInterface
    {
        throw new ActionNotImplementedException;
    }
}
