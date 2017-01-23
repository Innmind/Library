<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\Entity\HtmlPage\IdentityInterface;

final class FlaggedAsJournal
{
    private $identity;

    public function __construct(IdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }
}
