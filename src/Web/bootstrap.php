<?php
declare(strict_types = 1);

namespace Web;

use Web\{
    RequestHandler\CatchConflicts,
    RequestHandler\CatchNotFound,
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
use Innmind\Rest\Server\{
    Gateway,
    Routing\Prefix,
};
use Innmind\CommandBus\CommandBus;
use Innmind\Neo4j\DBAL\Connection;
use Innmind\Immutable\{
    Map,
    Set,
    Str,
};

function bootstrap(
    CommandBus $bus,
    Connection $dbal,
    HttpResourceRepository $httpResourceRepository,
    ImageRepository $imageRepository,
    HtmlPageRepository $htmlPageRepository,
    string $apiKey
): RequestHandler {
    $framework = framework();
    /**
     * @psalm-suppress MixedArgument
     * @psalm-suppress InvalidArgument
     * @psalm-suppress InvalidScalarArgument
     */
    $rest = $framework['bridge']['rest_server'](
        Map::of('string', Gateway::class)
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
        require __DIR__.'/config/rest.php',
        Route::of(
            new Route\Name('capabilities'),
            Str::of('OPTIONS /*')
        ),
        new Prefix('/api')
    );

    $auth = auth();
    /**
     * @psalm-suppress InvalidArgument
     * @psalm-suppress InvalidScalarArgument
     */
    $authenticate = $framework['authenticate'](
        $auth['validate_authorization_header'](
            $auth['any'](
                $auth['via_authorization'](
                    new ApiKey($apiKey)
                )
            )
        ),
        new Condition('~^/~'),
        Map::of('string', Fallback::class)
            (InvalidApiKey::class, new Unauthorized)
    );

    return $authenticate(
        new CatchConflicts(
            new CatchNotFound(
                new Router(
                    new RequestMatcher(
                        $rest['routes']
                    ),
                    $rest['controllers']
                )
            )
        )
    );
}
