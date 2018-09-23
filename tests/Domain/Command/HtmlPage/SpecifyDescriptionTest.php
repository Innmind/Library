<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyDescription,
    Entity\HtmlPage\Identity
};
use PHPUnit\Framework\TestCase;

class SpecifyDescriptionTest extends TestCase
{
    public function testInterface()
    {
        $command = new SpecifyDescription(
            $identity = $this->createMock(Identity::class),
            'foo'
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame('foo', $command->description());
    }
}
