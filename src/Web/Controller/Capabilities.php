<?php
declare(strict_types = 1);

namespace Web\Controller;

use Innmind\HttpFramework\Controller;
use Innmind\Rest\Server\Controller\Capabilities as RestCapabilities;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
};
use Innmind\Router\Route;
use Innmind\Immutable\MapInterface;

final class Capabilities implements Controller
{
    private $handle;

    public function __construct(RestCapabilities $handle)
    {
        $this->handle = $handle;
    }

    public function __invoke(
        ServerRequest $request,
        Route $route,
        MapInterface $arguments
    ): Response {
        return ($this->handle)($request);
    }
}
