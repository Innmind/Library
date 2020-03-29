<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\Domain\Identity;
use Innmind\Url\Authority\Host;

final class RegisterDomain
{
    private Identity $identity;
    private Host $host;

    public function __construct(Identity $identity, Host $host)
    {
        $this->identity = $identity;
        $this->host = $host;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function host(): Host
    {
        return $this->host;
    }
}
