<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\Image;

use Domain\{
    Event\Image\DescriptionAdded,
    Entity\Image\IdentityInterface,
    Entity\Image\Description
};
use PHPUnit\Framework\TestCase;

class DescriptionAddedTest extends TestCase
{
    public function testInterface()
    {
        $event = new DescriptionAdded(
            $identity = $this->createMock(IdentityInterface::class),
            $description = new Description('foobar')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($description, $event->description());
    }
}
