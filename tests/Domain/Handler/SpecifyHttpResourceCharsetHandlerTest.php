<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\SpecifyHttpResourceCharsetHandler,
    Command\SpecifyHttpResourceCharset,
    Repository\HttpResourceRepositoryInterface,
    Entity\HttpResource,
    Entity\HttpResource\IdentityInterface,
    Event\HttpResource\CharsetSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

class SpecifyHttpResourceCharsetHandlerTest extends \PHPunit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyHttpResourceCharsetHandler(
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
            new SpecifyHttpResourceCharset($identity, 'utf-8')
        ));
        $this->assertSame('utf-8', $resource->charset());
        $this->assertInstanceOf(
            CharsetSpecified::class,
            $resource->recordedEvents()->current()
        );
    }
}
