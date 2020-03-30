<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\Entity\HtmlPage\Identity;
use Innmind\Url\Url;

final class PreviewSpecified
{
    private Identity $identity;
    private Url $url;

    public function __construct(Identity $identity, Url $url)
    {
        $this->identity = $identity;
        $this->url = $url;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function url(): Url
    {
        return $this->url;
    }
}
