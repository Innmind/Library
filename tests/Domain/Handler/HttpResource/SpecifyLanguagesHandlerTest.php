<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler\HttpResource;

use Domain\{
    Handler\HttpResource\SpecifyLanguagesHandler,
    Command\HttpResource\SpecifyLanguages,
    Repository\HttpResourceRepositoryInterface,
    Entity\HttpResource,
    Entity\HttpResource\IdentityInterface,
    Event\HttpResource\LanguagesSpecified,
    Model\Language
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class SpecifyLanguagesHandlerTest extends TestCase
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
                $languages = (new Set(Language::class))
                    ->add(new Language('fr'))
            )
        ));
        $this->assertSame($languages, $resource->languages());
        $this->assertInstanceOf(
            LanguagesSpecified::class,
            $resource->recordedEvents()->current()
        );
    }
}
