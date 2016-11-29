<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Citation,
    Entity\Citation\IdentityInterface,
    Event\CitationRegistered
};
use Innmind\EventBus\ContainsRecordedEventsInterface;

class CitationTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanciation()
    {
        $entity = new Citation(
            $identity = $this->createMock(IdentityInterface::class),
            'foo'
        );

        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $entity);
        $this->assertSame($identity, $entity->identity());
        $this->assertSame('foo', $entity->text());
        $this->assertSame('foo', (string) $entity);
        $this->assertCount(0, $entity->recordedEvents());
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyText()
    {
        new Citation(
            $this->createMock(IdentityInterface::class),
            ''
        );
    }

    public function testRegister()
    {
        $entity = Citation::register(
            $identity = $this->createMock(IdentityInterface::class),
            'foo'
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
            'foo',
            $entity->recordedEvents()->current()->text()
        );
    }
}
