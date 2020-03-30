<?php
declare(strict_types = 1);

namespace App\Listener;

use Innmind\Filesystem\{
    Adapter,
    File\File,
    Name,
};
use Innmind\Stream\Readable\Stream;

final class StoreDomainEventListener
{
    private Adapter $filesystem;

    public function __construct(Adapter $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function __invoke(object $event): void
    {
        $class = get_class($event);

        if (substr($class, 0, 12) !== 'Domain\Event') {
            return;
        }

        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress MixedMethodCall
         */
        $identity = new Name($event->identity()->toString());
        $content = [];

        if ($this->filesystem->contains($identity)) {
            $file = $this->filesystem->get($identity);
            /** @var list<string> */
            $content = json_decode($file->content()->toString());
        }

        $content[] = serialize($event);
        $this->filesystem->add(
            new File(
                $identity,
                Stream::ofContent(json_encode($content)),
            )
        );
    }
}
