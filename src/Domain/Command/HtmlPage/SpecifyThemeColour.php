<?php
declare(strict_types = 1);

namespace Domain\Command\HtmlPage;

use Domain\Entity\HtmlPage\Identity;
use Innmind\Colour\RGBA;

final class SpecifyThemeColour
{
    private $identity;
    private $colour;

    public function __construct(
        Identity $identity,
        RGBA $colour
    ) {
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
