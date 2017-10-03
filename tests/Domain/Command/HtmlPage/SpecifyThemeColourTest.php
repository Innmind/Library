<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyThemeColour,
    Entity\HtmlPage\Identity
};
use Innmind\Colour\RGBA;
use PHPUnit\Framework\TestCase;

class SpecifyThemeColourTest extends TestCase
{
    public function testInterface()
    {
        $command = new SpecifyThemeColour(
            $identity = $this->createMock(Identity::class),
            $colour = RGBA::fromString('39f')
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($colour, $command->colour());
    }
}
