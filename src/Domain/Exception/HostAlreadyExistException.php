<?php
declare(strict_types = 1);

namespace Domain\Exception;

use Domain\Entity\Host;

final class HostAlreadyExistException extends LogicException
{
    private $host;

    public function __construct(Host $host)
    {
        $this->host = $host;
    }

    /**
     * Host that already exist
     */
    public function host(): Host
    {
        return $this->host;
    }
}
