<?php
declare(strict_types = 1);

namespace Web;

use Web\{
    RequestHandler\CatchConflicts,
    RequestHandler\CatchNotFound,
    RequestHandler\Debug,
    Controller\Capabilities,
    Controller\RestBridge,
    Gateway\HttpResourceGateway,
    Gateway\ImageGateway,
    Gateway\HtmlPageGateway,
    Authentication\ViaAuthorization\ApiKey,
    Exception\InvalidApiKey,
};
use Domain\Repository\{
    HttpResourceRepository,
    ImageRepository,
    HtmlPageRepository,
};
use function Innmind\HttpAuthentication\bootstrap as auth;
use function Innmind\HttpFramework\bootstrap as framework;
use Innmind\HttpFramework\{
    RequestHandler,
    Router,
    Controller,
    Authenticate\Condition,
    Authenticate\Fallback,
    Authenticate\Unauthorized,
};
use Innmind\Router\{
    Route,
    RequestMatcher\RequestMatcher,
};
use function Innmind\Rest\Server\bootstrap as rest;
use Innmind\Rest\Server\{
    Gateway,
    Routing\Prefix,
    Controller as RestController,
};
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Neo4j\DBAL\Connection;
use Innmind\Immutable\{
    Map,
    Set,
    Str,
};

function bootstrap(
    CommandBusInterface $bus,
    Connection $dbal,
    HttpResourceRepository $httpResourceRepository,
    ImageRepository $imageRepository,
    HtmlPageRepository $htmlPageRepository,
    string $apiKey,
    bool $debug = false
): RequestHandler {
    $rest = rest(
        (new Map('string', Gateway::class))
            ->put(
                'http_resource',
                new HttpResourceGateway(
                    new HttpResourceGateway\ResourceCreator($bus),
                    new HttpResourceGateway\ResourceAccessor($httpResourceRepository, $dbal),
                    new HttpResourceGateway\ResourceLinker($bus)
                )
            )
            ->put(
                'image',
                new ImageGateway(
                    new ImageGateway\ResourceCreator($bus),
                    new ImageGateway\ResourceAccessor($imageRepository, $dbal)
                )
            )
            ->put(
                'html_page',
                new HtmlPageGateway(
                    new HtmlPageGateway\ResourceCreator($bus),
                    new HtmlPageGateway\ResourceAccessor($htmlPageRepository, $dbal),
                    new HtmlPageGateway\ResourceLinker($bus)
                )
            ),
        Set::of('string', __DIR__.'/config/rest.yml'),
        null,
        null,
        new Prefix('/api')
    );

    $routesToDefinitions = Routes::from($rest['routes']);
    $capabilities = Route::of(
        new Route\Name('capabilities'),
        Str::of('OPTIONS /\*')
    );

    $controllers = Controllers::from(
        $rest['routes'],
        $rest['controller']['create'],
        $rest['controller']['get'],
        $rest['controller']['index'],
        $rest['controller']['options'],
        $rest['controller']['remove'],
        $rest['controller']['update'],
        $rest['controller']['link'],
        $rest['controller']['unlink'],
        static function(RestController $controller) use ($routesToDefinitions): Controller {
            return new RestBridge($controller, $routesToDefinitions);
        }
    );

    $auth = auth();
    $framework = framework();
    $authenticate = $framework['authenticate'](
        $auth['validate_authorization_header'](
            $auth['any'](
                $auth['via_authorization'](
                    new ApiKey($apiKey)
                )
            )
        ),
        new Condition('~^/~'),
        (new Map('string', Fallback::class))
            ->put(InvalidApiKey::class, new Unauthorized)
    );

    return $authenticate(
        new Debug(
            new CatchConflicts(
                new CatchNotFound(
                    new Router(
                        new RequestMatcher(
                            $routesToDefinitions->keys()->add($capabilities)
                        ),
                        $controllers->put(
                            'capabilities',
                            new Capabilities($rest['controller']['capabilities'])
                        )
                    )
                )
            ),
            $debug
        )
    );
}
