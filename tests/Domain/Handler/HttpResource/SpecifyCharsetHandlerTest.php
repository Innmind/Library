<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HttpResource;

use Domain\{
    Handler\HttpResource\SpecifyCharsetHandler,
    Command\HttpResource\SpecifyCharset,
    Repository\HttpResourceRepository,
    Entity\HttpResource,
    Entity\HttpResource\Identity,
    Entity\HttpResource\Charset,
    Event\HttpResource\CharsetSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use PHPUnit\Framework\TestCase;

class SpecifyCharsetHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyCharsetHandler(
            $repository = $this->createMock(HttpResourceRepository::class)
        );
        $identity = $this->createMock(Identity::class);
        $repository
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $resource = new HttpResource(
                    $identity,
                    $this->createMock(PathInterface::class),
                    $this->createMock(QueryInterface::class)
                )
            );

        $this->assertNull($handler(
            new SpecifyCharset($identity, $charset = new Charset('utf-8'))
        ));
        $this->assertSame($charset, $resource->charset());
        $this->assertInstanceOf(
            CharsetSpecified::class,
            $resource->recordedEvents()->current()
        );
    }
}
