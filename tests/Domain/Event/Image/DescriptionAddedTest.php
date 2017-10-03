<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\Image;

use Domain\{
    Event\Image\DescriptionAdded,
    Entity\Image\Identity,
    Entity\Image\Description
};
use PHPUnit\Framework\TestCase;

class DescriptionAddedTest extends TestCase
{
    public function testInterface()
    {
        $event = new DescriptionAdded(
            $identity = $this->createMock(Identity::class),
            $description = new Description('foobar')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($description, $event->description());
    }
}
