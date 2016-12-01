<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\AddImageDescription,
    Entity\Image\IdentityInterface
};

class AddImageDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new AddImageDescription(
            $identity = $this->createMock(IdentityInterface::class),
            'foobar'
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame('foobar', $command->description());
    }
}
