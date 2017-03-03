<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\DescriptionSpecified,
    Entity\HtmlPage\IdentityInterface
};
use PHPUnit\Framework\TestCase;

class DescriptionSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new DescriptionSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            $description = 'foo'
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($description, $event->description());
    }
}
