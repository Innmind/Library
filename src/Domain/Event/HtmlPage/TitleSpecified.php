<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\Entity\HtmlPage\IdentityInterface;

final class TitleSpecified
{
    private $identity;
    private $title;

    public function __construct(IdentityInterface $identity, string $title)
    {
        $this->identity = $identity;
        $this->title = $title;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function title(): string
    {
        return $this->title;
    }
}
