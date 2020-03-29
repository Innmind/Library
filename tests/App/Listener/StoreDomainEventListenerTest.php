<?php
declare(strict_types = 1);

namespace Tests\App\Listener;

use App\{
    Listener\StoreDomainEventListener,
    Entity\HtmlPage\Identity
};
use Domain\Event\HtmlPage\TitleSpecified;
use Innmind\Filesystem\{
    Adapter,
    File\File,
    Name,
};
use Innmind\Stream\Readable\Stream;
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
            ->method('contains')
            ->with(new Name($identity->toString()))
            ->willReturn(false);
        $event = new TitleSpecified(
            $identity,
            'some title'
        );
        $filesystem
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function($file) use ($identity, $event): bool {
                $content = json_decode($file->content()->toString(), true);

                return $file->name()->toString() === $identity->toString() &&
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
            ->method('contains')
            ->with(new Name($identity->toString()))
            ->willReturn(true);
        $filesystem
            ->expects($this->once())
            ->method('get')
            ->willReturn(
                File::named(
                    $identity->toString(),
                    Stream::ofContent(json_encode(['foo']))
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
                $content = json_decode($file->content()->toString(), true);

                return $file->name()->toString() === $identity->toString() &&
                    count($content) === 2 &&
                    $content[0] === 'foo' &&
                    unserialize($content[1]) == $event;
            }));

        $this->assertNull($listener($event));
    }
}
