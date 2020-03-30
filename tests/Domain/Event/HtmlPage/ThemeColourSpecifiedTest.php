<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\ThemeColourSpecified,
    Entity\HtmlPage\Identity
};
use Innmind\Colour\RGBA;
use PHPUnit\Framework\TestCase;

class ThemeColourSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new ThemeColourSpecified(
            $identity = $this->createMock(Identity::class),
            $colour = RGBA::of('39f')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($colour, $event->colour());
    }
}
