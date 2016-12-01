<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\Image;

use Domain\{
    Event\Image\DescriptionAdded,
    Entity\Image\IdentityInterface
};

class DescriptionAddedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new DescriptionAdded(
            $identity = $this->createMock(IdentityInterface::class),
            'foobar'
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame('foobar', $event->description());
    }
}
