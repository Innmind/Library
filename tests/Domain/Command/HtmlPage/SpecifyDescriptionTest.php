<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyDescription,
    Entity\HtmlPage\IdentityInterface
};

class SpecifyDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new SpecifyDescription(
            $identity = $this->createMock(IdentityInterface::class),
            'foo'
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame('foo', $command->description());
    }
}
