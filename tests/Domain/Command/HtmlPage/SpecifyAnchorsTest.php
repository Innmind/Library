<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyAnchors,
    Entity\HtmlPage\Identity,
    Entity\HtmlPage\Anchor
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class SpecifyAnchorsTest extends TestCase
{
    public function testInterface()
    {
        $command = new SpecifyAnchors(
            $identity = $this->createMock(Identity::class),
            $anchors = new Set(Anchor::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($anchors, $command->anchors());
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidAnchorSet()
    {
        new SpecifyAnchors(
            $this->createMock(Identity::class),
            new Set('string')
        );
    }
}
