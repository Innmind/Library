<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\Image;

use Domain\{
    Event\Image\DimensionSpecified,
    Entity\Image\IdentityInterface,
    Entity\Image\Dimension
};

class DimensionSpecifiedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new DimensionSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            $dimension = new Dimension(24, 42)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($dimension, $event->dimension());
    }
}
