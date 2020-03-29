<?php
declare(strict_types = 1);

namespace Domain\Exception;

use Domain\Entity\Host;

final class HostAlreadyExist extends LogicException
{
    private Host $host;

    public function __construct(Host $host)
    {
        $this->host = $host;
        parent::__construct();
    }

    /**
     * Host that already exist
     */
    public function host(): Host
    {
        return $this->host;
    }
}
