<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\ResourceAuthor,
    Entity\ResourceAuthor\Identity,
    Entity\Author\Identity as AuthorIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\ResourceAuthorRegistered,
};
use Innmind\TimeContinuum\PointInTime;
use Innmind\EventBus\ContainsRecordedEvents;
use PHPUnit\Framework\TestCase;

class ResourceAuthorTest extends TestCase
{
    public function testInstanciation()
    {
        $entity = new ResourceAuthor(
            $identity = $this->createMock(Identity::class),
            $author = $this->createMock(AuthorIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $asOf = $this->createMock(PointInTime::class)
        );

        $this->assertInstanceOf(ContainsRecordedEvents::class, $entity);
        $this->assertSame($identity, $entity->identity());
        $this->assertSame($author, $entity->author());
        $this->assertSame($resource, $entity->resource());
        $this->assertSame($asOf, $entity->asOf());
        $this->assertCount(0, $entity->recordedEvents());
    }

    public function testRegister()
    {
        $entity = ResourceAuthor::register(
            $identity = $this->createMock(Identity::class),
            $author = $this->createMock(AuthorIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $asOf = $this->createMock(PointInTime::class)
        );

        $this->assertInstanceOf(ResourceAuthor::class, $entity);
        $this->assertCount(1, $entity->recordedEvents());
        $this->assertInstanceOf(
            ResourceAuthorRegistered::class,
            $entity->recordedEvents()->first()
        );
        $this->assertSame(
            $identity,
            $entity->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $author,
            $entity->recordedEvents()->first()->author()
        );
        $this->assertSame(
            $resource,
            $entity->recordedEvents()->first()->resource()
        );
        $this->assertSame(
            $asOf,
            $entity->recordedEvents()->first()->asOf()
        );
    }
}
