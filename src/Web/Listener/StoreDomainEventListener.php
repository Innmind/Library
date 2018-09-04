<?php
declare(strict_types = 1);

namespace Web\Listener;

use Innmind\Filesystem\{
    Adapter,
    File\File,
    Stream\StringStream
};

final class StoreDomainEventListener
{
    private $filesystem;

    public function __construct(Adapter $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function __invoke($event): void
    {
        $class = get_class($event);

        if (substr($class, 0, 12) !== 'Domain\Event') {
            return;
        }

        $identity = (string) $event->identity();
        $content = [];

        if ($this->filesystem->has($identity)) {
            $file = $this->filesystem->get($identity);
            $content = json_decode((string) $file->content());
        }

        $content[] = serialize($event);
        $this->filesystem->add(
            new File(
                $identity,
                new StringStream(json_encode($content))
            )
        );
    }
}
