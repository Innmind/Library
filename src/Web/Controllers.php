<?php
declare(strict_types = 1);

namespace Web;

use Innmind\HttpFramework\Controller;
use Innmind\Rest\Server\{
    Routing\Routes,
    Controller as RestController,
};
use Innmind\Immutable\{
    MapInterface,
    Map,
};

final class Controllers
{
    /**
     * @return MapInterface<string, Controller>
     */
    public static function from(
        Routes $routes,
        RestController $create,
        RestController $get,
        RestController $index,
        RestController $options,
        RestController $remove,
        RestController $update,
        RestController $link,
        RestController $unlink,
        callable $bridge
    ): MapInterface {
        $controllers = new Map('string', Controller::class);

        foreach ($routes as $route) {
            $controller = ${(string) $route->action()};

            $controllers = $controllers->put(
                $route->name().'.'.$route->action(),
                $bridge($controller)
            );
        }

        return $controllers;
    }
}
