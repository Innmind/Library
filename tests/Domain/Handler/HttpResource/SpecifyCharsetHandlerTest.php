<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HttpResource;

use Domain\{
    Handler\HttpResource\SpecifyCharsetHandler,
    Command\HttpResource\SpecifyCharset,
    Repository\HttpResourceRepositoryInterface,
    Entity\HttpResource,
    Entity\HttpResource\IdentityInterface,
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
            $repository = $this->createMock(HttpResourceRepositoryInterface::class)
        );
        $identity = $this->createMock(IdentityInterface::class);
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
