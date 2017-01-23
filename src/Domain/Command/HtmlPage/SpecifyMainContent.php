<?php
declare(strict_types = 1);

namespace Domain\Command\HtmlPage;

use Domain\Entity\HtmlPage\IdentityInterface;

final class SpecifyMainContent
{
    private $identity;
    private $mainContent;

    public function __construct(IdentityInterface $identity, string $mainContent)
    {
        $this->identity = $identity;
        $this->mainContent = $mainContent;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function mainContent(): string
    {
        return $this->mainContent;
    }
}
