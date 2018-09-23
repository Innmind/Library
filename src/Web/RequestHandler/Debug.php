<?php
declare(strict_types = 1);

namespace Web\RequestHandler;

use Innmind\HttpFramework\RequestHandler;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
    StatusCode\StatusCode,
};
use Innmind\Filesystem\Stream\StringStream;
use Whoops\{
    Run,
    Handler\PrettyPageHandler,
};

final class Debug implements RequestHandler
{
    private $handle;
    private $debug;

    public function __construct(RequestHandler $handle, bool $debug)
    {
        $this->handle = $handle;
        $this->debug = $debug;
    }

    public function __invoke(ServerRequest $request): Response
    {
        if (!$this->debug) {
            return ($this->handle)($request);
        }

        try {
            return ($this->handle)($request);
        } catch (\Throwable $e) {
            $whoops = new Run;
            $whoops->pushHandler(new PrettyPageHandler);

            return new Response\Response(
                $code = StatusCode::of('INTERNAL_SERVER_ERROR'),
                $code->associatedReasonPhrase(),
                $request->protocolVersion(),
                null,
                new StringStream($whoops->handleException($e))
            );
        }
    }
}
