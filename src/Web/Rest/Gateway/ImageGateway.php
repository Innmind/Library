<?php
declare(strict_types = 1);

namespace Web\Rest\Gateway;

use Innmind\Rest\Server\{
    Gateway,
    ResourceListAccessor,
    ResourceAccessor,
    ResourceCreator,
    ResourceUpdater,
    ResourceRemover,
    ResourceLinker,
    ResourceUnlinker,
    Exception\ActionNotImplemented
};

final class ImageGateway implements Gateway
{
    private $resourceCreator;
    private $resourceAccessor;

    public function __construct(
        ImageGateway\ResourceCreator $resourceCreator,
        ImageGateway\ResourceAccessor $resourceAccessor
    ) {
        $this->resourceCreator = $resourceCreator;
        $this->resourceAccessor = $resourceAccessor;
    }

    public function resourceListAccessor(): ResourceListAccessor
    {
        throw new ActionNotImplemented;
    }

    public function resourceAccessor(): ResourceAccessor
    {
        return $this->resourceAccessor;
    }
    public function resourceCreator(): ResourceCreator
    {
        return $this->resourceCreator;
    }

    public function resourceUpdater(): ResourceUpdater
    {
        throw new ActionNotImplemented;
    }

    public function resourceRemover(): ResourceRemover
    {
        throw new ActionNotImplemented;
    }

    public function resourceLinker(): ResourceLinker
    {
        throw new ActionNotImplemented;
    }

    public function resourceUnlinker(): ResourceUnlinker
    {
        throw new ActionNotImplemented;
    }
}
