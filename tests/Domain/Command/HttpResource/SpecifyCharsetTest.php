<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HttpResource;

use Domain\{
    Command\HttpResource\SpecifyCharset,
    Entity\HttpResource\IdentityInterface,
    Entity\HttpResource\Charset
};
use PHPUnit\Framework\TestCase;

class SpecifyCharsetTest extends TestCase
{
    public function testInterface()
    {
        $command = new SpecifyCharset(
            $identity = $this->createMock(IdentityInterface::class),
            $charset = new Charset('utf-8')
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($charset, $command->charset());
    }
}
