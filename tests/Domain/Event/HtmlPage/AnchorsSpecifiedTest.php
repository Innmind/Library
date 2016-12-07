<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\AnchorsSpecified,
    Entity\HtmlPage\IdentityInterface
};
use Innmind\Immutable\SetInterface;

class AnchorsSpecifiedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new AnchorsSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            $anchors = $this->createMock(SetInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($anchors, $event->anchors());
    }
}
