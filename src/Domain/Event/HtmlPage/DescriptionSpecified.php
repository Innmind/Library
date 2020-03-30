<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\Entity\HtmlPage\Identity;

final class DescriptionSpecified
{
    private Identity $identity;
    private string $description;

    public function __construct(Identity $identity, string $description)
    {
        $this->identity = $identity;
        $this->description = $description;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function description(): string
    {
        return $this->description;
    }
}
