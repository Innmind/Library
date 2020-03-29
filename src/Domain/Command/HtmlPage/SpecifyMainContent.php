<?php
declare(strict_types = 1);

namespace Domain\Command\HtmlPage;

use Domain\Entity\HtmlPage\Identity;

final class SpecifyMainContent
{
    private Identity $identity;
    private string $mainContent;

    public function __construct(Identity $identity, string $mainContent)
    {
        $this->identity = $identity;
        $this->mainContent = $mainContent;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function mainContent(): string
    {
        return $this->mainContent;
    }
}
