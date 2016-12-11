<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\Image;

use Domain\{
    Command\Image\AddDescription,
    Entity\Image\IdentityInterface
};

class AddDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new AddDescription(
            $identity = $this->createMock(IdentityInterface::class),
            'foobar'
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame('foobar', $command->description());
    }
}
