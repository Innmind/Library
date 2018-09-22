<?php
declare(strict_types = 1);

require __DIR__.'/../vendor/autoload.php';

use function App\bootstrap as app;
use function Web\bootstrap as web;
use Innmind\HttpServer\Main;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
};
use Innmind\Url\Url;
use Innmind\HttpFramework\Environment;
use Innmind\Filesystem\Adapter\FilesystemAdapter;
use Innmind\Immutable\MapInterface;

new class extends Main
{
    protected function main(ServerRequest $request): Response
    {
        $environment = $this->environment($request);

        return $this->handle($request, $environment);
    }

    /**
     * @return MapInterface<string, mixed>
     */
    private function environment(ServerRequest $request): MapInterface
    {
        return Environment::camelize(
            __DIR__.'/../config/.env',
            $request->environment()
        );
    }

    /**
     * @param MapInterface<string, mixed> $environment
     */
    private function handle(ServerRequest $request, MapInterface $environment): Response
    {
        $app = app(
            Url::fromString($environment->get('neo4j')),
            new FilesystemAdapter(__DIR__.'/../var/innmind/domain_events')
        );
        $handle = web(
            $app['command_bus'],
            $app['dbal'],
            $app['repository']['http_resource'],
            $app['repository']['image'],
            $app['repository']['html_page'],
            $environment->contains('debug')
        );

        return $handle($request);
    }
};