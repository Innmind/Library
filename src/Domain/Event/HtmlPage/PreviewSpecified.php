<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\Entity\HtmlPage\IdentityInterface;
use Innmind\Url\UrlInterface;

final class PreviewSpecified
{
    private $identity;
    private $url;

    public function __construct(IdentityInterface $identity, UrlInterface $url)
    {
        $this->identity = $identity;
        $this->url = $url;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function url(): UrlInterface
    {
        return $this->url;
    }
}
