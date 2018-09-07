<?php
declare(strict_types = 1);

namespace Web\Controller;

use Innmind\HttpFramework\Controller;
use Innmind\Rest\Server\{
    Controller as RestController,
    Definition\HttpResource,
    Identity\Identity,
};
use Innmind\Http\Message\{
    ServerRequest,
    Response,
};
use Innmind\Router\Route;
use Innmind\Immutable\MapInterface;

final class RestBridge implements Controller
{
    private $handle;
    private $definitions;

    public function __construct(RestController $handle, MapInterface $definitions)
    {
        if (
            (string) $definitions->keyType() !== Route::class ||
            (string) $definitions->valueType() !== HttpResource::class
        ) {
            throw new \TypeError(sprintf(
                'Argument 2 must be of type MapInterface<%s, %s>',
                Route::class,
                HttpResource::class
            ));
        }

        $this->handle = $handle;
        $this->definitions = $definitions;
    }

    public function __invoke(
        ServerRequest $request,
        Route $route,
        MapInterface $arguments
    ): Response {
        if ($arguments->contains('identity')) {
            $identity = new Identity(
                $arguments->get('identity')
            );
        }

        return ($this->handle)(
            $request,
            $this->definitions->get($route),
            $identity ?? null
        );
    }
}
