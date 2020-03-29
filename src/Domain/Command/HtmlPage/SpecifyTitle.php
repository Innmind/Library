<?php
declare(strict_types = 1);

namespace Domain\Command\HtmlPage;

use Domain\Entity\HtmlPage\Identity;

final class SpecifyTitle
{
    private Identity $identity;
    private string $title;

    public function __construct(Identity $identity, string $title)
    {
        $this->identity = $identity;
        $this->title = $title;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function title(): string
    {
        return $this->title;
    }
}
