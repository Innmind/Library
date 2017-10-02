<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Listener;

use AppBundle\{
    Listener\StoreDomainEventListener,
    Entity\HtmlPage\Identity
};
use Domain\Event\HtmlPage\TitleSpecified;
use Innmind\Filesystem\{
    Adapter,
    File\File,
    Stream\StringStream
};
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class StoreDomainEventListenerTest extends TestCase
{
    public function testDoesntHandleNonDomainEvent()
    {
        $listener = new StoreDomainEventListener(
            $filesystem = $this->createMock(Adapter::class)
        );
        $filesystem
            ->expects($this->never())
            ->method('add');

        $this->assertNull($listener(new \stdClass));
    }

    public function testStoreFirstEvent()
    {
        $listener = new StoreDomainEventListener(
            $filesystem = $this->createMock(Adapter::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $filesystem
            ->expects($this->once())
            ->method('has')
            ->with((string) $identity)
            ->willReturn(false);
        $event = new TitleSpecified(
            $identity,
            'some title'
        );
        $filesystem
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function($file) use ($identity, $event): bool {
                $content = json_decode((string) $file->content(), true);

                return (string) $file->name() === (string) $identity &&
                    count($content) === 1 &&
                    unserialize($content[0]) == $event;
            }));

        $this->assertNull($listener($event));
    }

    public function testStoreNewEvent()
    {
        $listener = new StoreDomainEventListener(
            $filesystem = $this->createMock(Adapter::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $filesystem
            ->expects($this->once())
            ->method('has')
            ->with((string) $identity)
            ->willReturn(true);
        $filesystem
            ->expects($this->once())
            ->method('get')
            ->willReturn(
                new File(
                    (string) $identity,
                    new StringStream(json_encode(['foo']))
                )
            );
        $event = new TitleSpecified(
            $identity,
            'some title'
        );
        $filesystem
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function($file) use ($identity, $event): bool {
                $content = json_decode((string) $file->content(), true);

                return (string) $file->name() === (string) $identity &&
                    count($content) === 2 &&
                    $content[0] === 'foo' &&
                    unserialize($content[1]) == $event;
            }));

        $this->assertNull($listener($event));
    }
}
