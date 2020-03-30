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
    Model\Language,
    Exception\DomainException,
};
use Innmind\Url\{
    Path,
    Query,
};
use Innmind\EventBus\ContainsRecordedEvents;
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class HttpResourceTest extends TestCase
{
    public function testInterface()
    {
        $resource = new HttpResource(
            $identity = $this->createMock(Identity::class),
            $path = Path::none(),
            $query = Query::none()
        );

        $this->assertInstanceOf(ContainsRecordedEvents::class, $resource);
        $this->assertSame($identity, $resource->identity());
        $this->assertSame($path, $resource->path());
        $this->assertSame($query, $resource->query());
        $this->assertCount(0, $resource->recordedEvents());
    }

    public function testRegister()
    {
        $resource = HttpResource::register(
            $identity = $this->createMock(Identity::class),
            $path = Path::none(),
            $query = Query::none()
        );

        $this->assertInstanceOf(HttpResource::class, $resource);
        $this->assertSame($identity, $resource->identity());
        $this->assertSame($path, $resource->path());
        $this->assertSame($query, $resource->query());
        $this->assertCount(1, $resource->recordedEvents());
        $this->assertInstanceOf(
            HttpResourceRegistered::class,
            $resource->recordedEvents()->first()
        );
        $this->assertSame($identity, $resource->recordedEvents()->first()->identity());
        $this->assertSame($path, $resource->recordedEvents()->first()->path());
        $this->assertSame($query, $resource->recordedEvents()->first()->query());
    }

    public function testSpecifyLanguages()
    {
        $resource = new HttpResource(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
        );

        $this->assertCount(0, $resource->languages());
        $this->assertSame(
            Language::class,
            (string) $resource->languages()->type()
        );
        $this->assertSame(
            $resource,
            $resource->specifyLanguages(
                $languages = (Set::of(Language::class))
                    ->add(new Language('fr'))
            )
        );
        $this->assertSame($languages, $resource->languages());
        $this->assertCount(1, $resource->recordedEvents());
        $this->assertInstanceOf(
            LanguagesSpecified::class,
            $resource->recordedEvents()->first()
        );
        $this->assertSame(
            $resource->identity(),
            $resource->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $resource->languages(),
            $resource->recordedEvents()->first()->languages()
        );
    }

    public function testThrowWhenInvalidLanguagesType()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 1 must be of type Set<Domain\Model\Language>');

        (new HttpResource(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
        ))->specifyLanguages((Set::of('int'))->add(42));
    }

    public function testThrowWhenEmptyLanguagesSet()
    {
        $this->expectException(DomainException::class);

        (new HttpResource(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
        ))->specifyLanguages(Set::of(Language::class));
    }

    public function testSpecifyCharset()
    {
        $resource = new HttpResource(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
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
            $resource->recordedEvents()->first()
        );
        $this->assertSame(
            $resource->identity(),
            $resource->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $resource->charset(),
            $resource->recordedEvents()->first()->charset()
        );
    }
}
