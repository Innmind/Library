<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HttpResource;

use Domain\{
    Command\HttpResource\SpecifyCharset,
    Entity\HttpResource\IdentityInterface
};

class SpecifyCharsetTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new SpecifyCharset(
            $identity = $this->createMock(IdentityInterface::class),
            'utf-8'
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame('utf-8', $command->charset());
    }
}
