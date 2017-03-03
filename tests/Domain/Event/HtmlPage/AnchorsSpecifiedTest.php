<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\AnchorsSpecified,
    Entity\HtmlPage\IdentityInterface,
    Entity\HtmlPage\Anchor
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class AnchorsSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new AnchorsSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            $anchors = new Set(Anchor::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($anchors, $event->anchors());
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidAnchorSet()
    {
        new AnchorsSpecified(
            $this->createMock(IdentityInterface::class),
            new Set('string')
        );
    }
}
