<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\Domain\Identity;
use Innmind\Url\Authority\HostInterface;

final class RegisterDomain
{
    private Identity $identity;
    private HostInterface $host;

    public function __construct(Identity $identity, HostInterface $host)
    {
        $this->identity = $identity;
        $this->host = $host;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function host(): HostInterface
    {
        return $this->host;
    }
}
