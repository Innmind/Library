<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\Entity\HtmlPage\Identity;
use Innmind\Url\UrlInterface;

final class PreviewSpecified
{
    private $identity;
    private $url;

    public function __construct(Identity $identity, UrlInterface $url)
    {
        $this->identity = $identity;
        $this->url = $url;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function url(): UrlInterface
    {
        return $this->url;
    }
}
