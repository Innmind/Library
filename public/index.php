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
use function Innmind\Debug\bootstrap as debug;
use function Innmind\SilentCartographer\bootstrap as cartographer;
use function Innmind\Stack\curry;
use Innmind\HttpFramework\RequestHandler;
use Innmind\OperatingSystem\OperatingSystem;
use Innmind\Debug\{
    CodeEditor,
    Profiler\Section\CaptureAppGraph,
};
use Innmind\Immutable\{
    MapInterface,
    Set,
};

new class extends Main
{
    private $handle;

    protected function preload(OperatingSystem $os, Environment $environment): void
    {
        $os = cartographer($os)['http_server'](Url::fromString(__DIR__));

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

        $domain = curry('Domain\bootstrap');

        if ($debug) {
            $debugger = debug(
                $os,
                Url::fromString($environment->get('profiler')),
                $environment,
                CodeEditor::sublimeText(),
                Set::of('string', CaptureAppGraph::class)
            );
            $os = $debugger['os']();
            $domain = static function(...$arguments) use ($domain, $debugger) {
                return $domain(...$arguments)->map(static function(string $command, callable $handler) use ($debugger) {
                    return $debugger['callable']($handler);
                });
            };
        }

        $app = app(
            $domain,
            $os->remote()->http(),
            Url::fromString($environment->get('neo4j')),
            $os->filesystem()->mount(new Path(__DIR__.'/../var/innmind/domain_events')),
            $dsns,
            $debug ? null : 'error'
        );
        $commandBus = $app['command_bus'];

        if ($debug) {
            $commandBus = $debugger['command_bus']($commandBus);
        }

        $this->handle = web(
            $commandBus,
            $app['dbal'],
            $app['repository']['http_resource'],
            $app['repository']['image'],
            $app['repository']['html_page'],
            $environment->get('apiKey')
        );

        if ($debug) {
            $this->handle = $debugger['http']($this->handle);
        }
    }
    protected function main(ServerRequest $request, OperatingSystem $os): Response
    {
        return ($this->handle)($request);
    }
};
