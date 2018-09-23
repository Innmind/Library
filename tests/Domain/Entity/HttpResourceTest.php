<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\HttpResource,
    Entity\HttpResource\Identity,
    Entity\HttpResource\Charset,
    Event\HttpResourceRegistered,
    Event\HttpResource\LanguagesSpecified,
    Event\HttpResource\CharsetSpecified,
    Model\Language
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\EventBus\ContainsRecordedEventsInterface;
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class HttpResourceTest extends TestCase
{
    public function testInterface()
    {
        $resource = new HttpResource(
            $identity = $this->createMock(Identity::class),
            $path = $this->createMock(PathInterface::class),
            $query = $this->createMock(QueryInterface::class)
        );

        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $resource);
        $this->assertSame($identity, $resource->identity());
        $this->assertSame($path, $resource->path());
        $this->assertSame($query, $resource->query());
        $this->assertCount(0, $resource->recordedEvents());
    }

    public function testRegister()
    {
        $resource = HttpResource::register(
            $identity = $this->createMock(Identity::class),
            $path = $this->createMock(PathInterface::class),
            $query = $this->createMock(QueryInterface::class)
        );

        $this->assertInstanceOf(HttpResource::class, $resource);
        $this->assertSame($identity, $resource->identity());
        $this->assertSame($path, $resource->path());
        $this->assertSame($query, $resource->query());
        $this->assertCount(1, $resource->recordedEvents());
        $this->assertInstanceOf(
            HttpResourceRegistered::class,
            $resource->recordedEvents()->current()
        );
        $this->assertSame($identity, $resource->recordedEvents()->current()->identity());
        $this->assertSame($path, $resource->recordedEvents()->current()->path());
        $this->assertSame($query, $resource->recordedEvents()->current()->query());
    }

    public function testSpecifyLanguages()
    {
        $resource = new HttpResource(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertCount(0, $resource->languages());
        $this->assertSame(
            Language::class,
            (string) $resource->languages()->type()
        );
        $this->assertSame(
            $resource,
            $resource->specifyLanguages(
                $languages = (new Set(Language::class))
                    ->add(new Language('fr'))
            )
        );
        $this->assertSame($languages, $resource->languages());
        $this->assertCount(1, $resource->recordedEvents());
        $this->assertInstanceOf(
            LanguagesSpecified::class,
            $resource->recordedEvents()->current()
        );
        $this->assertSame(
            $resource->identity(),
            $resource->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $resource->languages(),
            $resource->recordedEvents()->current()->languages()
        );
    }

    /**
     * @expectedException TypeError
     * @expectedExceptionMessage Argument 1 must be of type SetInterface<Domain\Model\Language>
     */
    public function testThrowWhenInvalidLanguagesType()
    {
        (new HttpResource(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        ))->specifyLanguages((new Set('int'))->add(42));
    }

    /**
     * @expectedException Domain\Exception\DomainException
     */
    public function testThrowWhenEmptyLanguagesSet()
    {
        (new HttpResource(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        ))->specifyLanguages(new Set(Language::class));
    }

    public function testSpecifyCharset()
    {
        $resource = new HttpResource(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertFalse($resource->hasCharset());
        $this->assertSame(
            $resource,
            $resource->specifyCharset($charset = new Charset('utf-8'))
        );
        $this->assertTrue($resource->hasCharset());
        $this->assertSame($charset, $resource->charset());
        $this->assertCount(1, $resource->recordedEvents());
        $this->assertInstanceOf(
            CharsetSpecified::class,
            $resource->recordedEvents()->current()
        );
        $this->assertSame(
            $resource->identity(),
            $resource->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $resource->charset(),
            $resource->recordedEvents()->current()->charset()
        );
    }
}
