<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyTitle,
    Entity\HtmlPage\IdentityInterface
};

class SpecifyTitleTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new SpecifyTitle(
            $identity = $this->createMock(IdentityInterface::class),
            'foo'
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame('foo', $command->title());
    }
}
