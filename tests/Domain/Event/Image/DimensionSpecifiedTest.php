<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\Image;

use Domain\{
    Event\Image\DimensionSpecified,
    Entity\Image\Identity,
    Entity\Image\Dimension
};
use PHPUnit\Framework\TestCase;

class DimensionSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new DimensionSpecified(
            $identity = $this->createMock(Identity::class),
            $dimension = new Dimension(24, 42)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($dimension, $event->dimension());
    }
}
