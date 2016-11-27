<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\Domain\IdentityInterface;
use Innmind\Url\Authority\HostInterface;

final class RegisterDomain
{
    private $identity;
    private $host;

    public function __construct(IdentityInterface $identity, HostInterface $host)
    {
        $this->identity = $identity;
        $this->host = $host;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function host(): HostInterface
    {
        return $this->host;
    }
}
