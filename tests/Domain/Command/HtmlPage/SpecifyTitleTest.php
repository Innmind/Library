<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyTitle,
    Entity\HtmlPage\Identity
};
use PHPUnit\Framework\TestCase;

class SpecifyTitleTest extends TestCase
{
    public function testInterface()
    {
        $command = new SpecifyTitle(
            $identity = $this->createMock(Identity::class),
            'foo'
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame('foo', $command->title());
    }
}
