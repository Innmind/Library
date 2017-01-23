<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\RegisterCitation,
    Entity\Citation\IdentityInterface,
    Entity\Citation\Text
};

class RegisterCitationTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new RegisterCitation(
            $identity = $this->createMock(IdentityInterface::class),
            $text = new Text('foo')
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($text, $command->text());
    }
}
