<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\AnchorsSpecified,
    Entity\HtmlPage\Identity,
    Entity\HtmlPage\Anchor
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class AnchorsSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new AnchorsSpecified(
            $identity = $this->createMock(Identity::class),
            $anchors = new Set(Anchor::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($anchors, $event->anchors());
    }

    public function testThrowWhenInvalidAnchorSet()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 2 must be of type SetInterface<Domain\Entity\HtmlPage\Anchor>');

        new AnchorsSpecified(
            $this->createMock(Identity::class),
            new Set('string')
        );
    }
}
