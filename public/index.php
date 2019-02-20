<?php
declare(strict_types = 1);

require __DIR__.'/../vendor/autoload.php';

use function App\bootstrap as app;
use function Web\bootstrap as web;
use Innmind\HttpServer\Main;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
    Environment,
};
use Innmind\Url\{
    UrlInterface,
    Url,
    Path,
};
use function Innmind\HttpFramework\env;
use Innmind\HttpFramework\RequestHandler;
use Innmind\OperatingSystem\OperatingSystem;
use Innmind\Immutable\{
    MapInterface,
    Set,
};

new class extends Main
{
    private $handle;

    protected function preload(OperatingSystem $os, Environment $environment): void
    {
        if ($this->handle) {
            return;
        }

        $environment = env(
            $environment,
            $os->filesystem()->mount(new Path(__DIR__.'/../config'))
        );

        $debug = $environment->contains('debug');

        $dsns = Set::of(
            UrlInterface::class,
            Url::fromString('file://'.__DIR__.'/../var/log.txt')
        );

        if ($environment->contains('sentry')) {
            $dsns = $dsns->add(
                Url::fromString('sentry://'.$environment->get('sentry'))
            );
        }

        $app = app(
            $os->remote()->http(),
            Url::fromString($environment->get('neo4j')),
            $os->filesystem()->mount(new Path(__DIR__.'/../var/innmind/domain_events')),
            $dsns,
            $debug ? null : 'error'
        );

        $this->handle = web(
            $app['command_bus'],
            $app['dbal'],
            $app['repository']['http_resource'],
            $app['repository']['image'],
            $app['repository']['html_page'],
            $environment->get('apiKey'),
            $debug
        );
    }
    protected function main(ServerRequest $request, OperatingSystem $os): Response
    {
        return ($this->handle)($request);
    }

};
