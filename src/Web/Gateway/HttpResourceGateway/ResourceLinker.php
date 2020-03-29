<?php
declare(strict_types = 1);

namespace Web\Gateway\HttpResourceGateway;

use App\Entity\{
    Reference\Identity as ReferenceIdentity,
    HttpResource\Identity as ResourceIdentity,
};
use Domain\{
    Command\ReferResource,
    Exception\ReferenceAlreadyExist,
};
use Innmind\Rest\Server\{
    ResourceLinker as ResourceLinkerInterface,
    Reference,
    Link,
};
use Innmind\CommandBus\CommandBus;
use Ramsey\Uuid\Uuid;

final class ResourceLinker implements ResourceLinkerInterface
{
    private CommandBus $handle;

    public function __construct(CommandBus $handle)
    {
        $this->handle = $handle;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Reference $from, Link ...$links): void
    {
        $from = new ResourceIdentity($from->identity()->toString());

        foreach ($links as $link) {
            switch ($link->relationship()) {
                case 'referrer':
                    $this->refer($from, $link->reference());
                    break;
            }
        }
    }

    private function refer(ResourceIdentity $from, Reference $to): void
    {
        try {
            ($this->handle)(
                new ReferResource(
                    new ReferenceIdentity((string) Uuid::uuid4()),
                    $from,
                    new ResourceIdentity($to->identity()->toString())
                )
            );
        } catch (ReferenceAlreadyExist $e) {
            //pass
        }
    }
}
