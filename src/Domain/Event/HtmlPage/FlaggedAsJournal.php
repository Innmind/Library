<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\Entity\HtmlPage\Identity;

final class FlaggedAsJournal
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
