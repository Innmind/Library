<?php
declare(strict_types = 1);

namespace Web\Gateway;

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

final class HtmlPageGateway implements Gateway
{
    private HtmlPageGateway\ResourceCreator $resourceCreator;
    private HtmlPageGateway\ResourceAccessor $resourceAccessor;
    private HtmlPageGateway\ResourceLinker $resourceLinker;

    public function __construct(
        HtmlPageGateway\ResourceCreator $resourceCreator,
        HtmlPageGateway\ResourceAccessor $resourceAccessor,
        HtmlPageGateway\ResourceLinker $resourceLinker
    ) {
        $this->resourceCreator = $resourceCreator;
        $this->resourceAccessor = $resourceAccessor;
        $this->resourceLinker = $resourceLinker;
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
        return $this->resourceLinker;
    }

    public function resourceUnlinker(): ResourceUnlinker
    {
        throw new ActionNotImplemented;
    }
}
