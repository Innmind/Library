<?php
declare(strict_types = 1);

namespace Domain\Command\HtmlPage;

use Domain\Entity\HtmlPage\Identity;

final class FlagAsJournal
{
    private $identity;

    public function __construct(Identity $identity)
    {
        $this->identity = $identity;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }
}
