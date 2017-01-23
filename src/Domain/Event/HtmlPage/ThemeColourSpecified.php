<?php
declare(strict_types = 1);

namespace Domain\Event\HtmlPage;

use Domain\Entity\HtmlPage\IdentityInterface;
use Innmind\Colour\RGBA;

final class ThemeColourSpecified
{
    private $identity;
    private $colour;

    public function __construct(IdentityInterface $identity, RGBA $colour)
    {
        $this->identity = $identity;
        $this->colour = $colour;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function colour(): RGBA
    {
        return $this->colour;
    }
}
