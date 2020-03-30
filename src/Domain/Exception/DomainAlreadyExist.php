<?php
declare(strict_types = 1);

namespace Domain\Exception;

use Domain\Entity\Domain;

final class DomainAlreadyExist extends LogicException
{
    private Domain $domain;

    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    /**
     * Domain that already exist
     */
    public function domain(): Domain
    {
        return $this->domain;
    }
}
