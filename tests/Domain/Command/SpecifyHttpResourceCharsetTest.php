<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\SpecifyHttpResourceCharset,
    Entity\HttpResource\IdentityInterface
};

class SpecifyHttpResourceCharsetTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new SpecifyHttpResourceCharset(
            $identity = $this->createMock(IdentityInterface::class),
            'utf-8'
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame('utf-8', $command->charset());
    }
}
