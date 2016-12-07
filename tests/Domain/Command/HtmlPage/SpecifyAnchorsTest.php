<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyAnchors,
    Entity\HtmlPage\IdentityInterface
};
use Innmind\Immutable\SetInterface;

class SpecifyAnchorsTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new SpecifyAnchors(
            $identity = $this->createMock(IdentityInterface::class),
            $anchors = $this->createMock(SetInterface::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($anchors, $command->anchors());
    }
}
