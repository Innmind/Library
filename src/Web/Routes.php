<?php
declare(strict_types = 1);

namespace Web;

use Innmind\Rest\Server\{
    Routing\Routes as RestRoutes,
    Definition\HttpResource,
    Action,
};
use Innmind\Router\Route;
use Innmind\Http\Message\Method\Method;
use Innmind\Immutable\{
    MapInterface,
    Map,
};

final class Routes
{
    /**
     * @return MapInterface<Route, HttpResource>
     */
    public static function from(RestRoutes $routes): MapInterface
    {
        $map = new Map(Route::class, HttpResource::class);

        foreach ($routes as $route) {
            switch ($route->action()) {
                case Action::list():
                case Action::get():
                    $method = Method::get();
                    break;

                case Action::create():
                    $method = Method::post();
                    break;

                case Action::update():
                    $method = Method::put();
                    break;

                case Action::remove():
                    $method = Method::delete();
                    break;

                case Action::link():
                    $method = Method::link();
                    break;

                case Action::unlink():
                    $method = Method::unlink();
                    break;

                case Action::options():
                    $method = Method::options();
                    break;
            }

            $map = $map->put(
                new Route(
                    new Route\Name((string) $route->name()),
                    $route->template(),
                    $method
                ),
                $route->definition()
            );
        }

        return $map;
    }
}
