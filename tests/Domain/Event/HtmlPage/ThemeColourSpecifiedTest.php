<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\ThemeColourSpecified,
    Entity\HtmlPage\IdentityInterface
};
use Innmind\Colour\RGBA;

class ThemeColourSpecifiedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new ThemeColourSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            $colour = RGBA::fromString('39f')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($colour, $event->colour());
    }
}
