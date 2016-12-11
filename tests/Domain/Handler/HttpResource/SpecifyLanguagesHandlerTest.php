<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HttpResource;

use Domain\{
    Handler\HttpResource\SpecifyLanguagesHandler,
    Command\HttpResource\SpecifyLanguages,
    Repository\HttpResourceRepositoryInterface,
    Entity\HttpResource,
    Entity\HttpResource\IdentityInterface,
    Event\HttpResource\LanguagesSpecified
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Immutable\Set;

class SpecifyLanguagesHandlerTest extends \PHPunit_Framework_TestCase
{
    public function testExecution()
    {
        $handler = new SpecifyLanguagesHandler(
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
            new SpecifyLanguages(
                $identity,
                $languages = (new Set('string'))->add('fr')
            )
        ));
        $this->assertSame($languages, $resource->languages());
        $this->assertInstanceOf(
            LanguagesSpecified::class,
            $resource->recordedEvents()->current()
        );
    }
}
