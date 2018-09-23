<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\MainContentSpecified,
    Entity\HtmlPage\Identity
};
use PHPUnit\Framework\TestCase;

class MainContentSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new MainContentSpecified(
            $identity = $this->createMock(Identity::class),
            $mainContent = 'foo'
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($mainContent, $event->mainContent());
    }
}
