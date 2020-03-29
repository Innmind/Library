<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\Entity\HtmlPage\Identity;
use Innmind\Colour\RGBA;

final class ThemeColourSpecified
{
    private Identity $identity;
    private RGBA $colour;

    public function __construct(Identity $identity, RGBA $colour)
    {
        $this->identity = $identity;
        $this->colour = $colour;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function colour(): RGBA
    {
        return $this->colour;
    }
}
