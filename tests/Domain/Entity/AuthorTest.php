<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Author,
    Entity\Author\Identity,
    Entity\Author\Name,
    Event\AuthorRegistered
};
use Innmind\EventBus\ContainsRecordedEventsInterface;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    public function testInstanciation()
    {
        $entity = new Author(
            $identity = $this->createMock(Identity::class),
            $name = new Name('John Doe')
        );

        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $entity);
        $this->assertSame($identity, $entity->identity());
        $this->assertSame($name, $entity->name());
        $this->assertSame('John Doe', (string) $entity);
        $this->assertCount(0, $entity->recordedEvents());
    }

    public function testRegister()
    {
        $entity = Author::register(
            $identity = $this->createMock(Identity::class),
            $name = new Name('John Doe')
        );

        $this->assertInstanceOf(Author::class, $entity);
        $this->assertCount(1, $entity->recordedEvents());
        $this->assertInstanceOf(
            AuthorRegistered::class,
            $entity->recordedEvents()->current()
        );
        $this->assertSame(
            $identity,
            $entity->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $name,
            $entity->recordedEvents()->current()->name()
        );
    }
}
