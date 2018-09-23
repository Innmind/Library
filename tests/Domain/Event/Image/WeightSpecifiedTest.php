<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\Image;

use Domain\{
    Event\Image\WeightSpecified,
    Entity\Image\Identity,
    Entity\Image\Weight
};
use PHPUnit\Framework\TestCase;

class WeightSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new WeightSpecified(
            $identity = $this->createMock(Identity::class),
            $weight = new Weight(42)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($weight, $event->weight());
    }
}
