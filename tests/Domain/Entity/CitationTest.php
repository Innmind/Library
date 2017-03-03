<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Citation,
    Entity\Citation\IdentityInterface,
    Entity\Citation\Text,
    Event\CitationRegistered
};
use Innmind\EventBus\ContainsRecordedEventsInterface;
use PHPUnit\Framework\TestCase;

class CitationTest extends TestCase
{
    public function testInstanciation()
    {
        $entity = new Citation(
            $identity = $this->createMock(IdentityInterface::class),
            $text = new Text('foo')
        );

        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $entity);
        $this->assertSame($identity, $entity->identity());
        $this->assertSame($text, $entity->text());
        $this->assertSame('foo', (string) $entity);
        $this->assertCount(0, $entity->recordedEvents());
    }

    public function testRegister()
    {
        $entity = Citation::register(
            $identity = $this->createMock(IdentityInterface::class),
            $text = new Text('foo')
        );

        $this->assertInstanceOf(Citation::class, $entity);
        $this->assertCount(1, $entity->recordedEvents());
        $this->assertInstanceOf(
            CitationRegistered::class,
            $entity->recordedEvents()->current()
        );
        $this->assertSame(
            $identity,
            $entity->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $text,
            $entity->recordedEvents()->current()->text()
        );
    }
}
