<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyThemeColour,
    Entity\HtmlPage\IdentityInterface
};
use Innmind\Colour\RGBA;

class SpecifyThemeColourTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new SpecifyThemeColour(
            $identity = $this->createMock(IdentityInterface::class),
            $colour = RGBA::fromString('39f')
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($colour, $command->colour());
    }
}
