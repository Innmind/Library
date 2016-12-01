<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\Image;

use Domain\{
    Event\Image\WeightSpecified,
    Entity\Image\IdentityInterface
};

class WeightSpecifiedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new WeightSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            42
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame(42, $event->weight());
    }
}
