<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyMainContent,
    Entity\HtmlPage\Identity
};
use PHPUnit\Framework\TestCase;

class SpecifyMainContentTest extends TestCase
{
    public function testInterface()
    {
        $command = new SpecifyMainContent(
            $identity = $this->createMock(Identity::class),
            'foo'
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame('foo', $command->mainContent());
    }
}
