<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\Image;

use Domain\{
    Command\Image\SpecifyDimension,
    Entity\Image\IdentityInterface,
    Entity\Image\Dimension
};
use PHPUnit\Framework\TestCase;

class SpecifyDimensionTest extends TestCase
{
    public function testInterface()
    {
        $command = new SpecifyDimension(
            $identity = $this->createMock(IdentityInterface::class),
            $dimension = new Dimension(12, 21)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($dimension, $command->dimension());
    }
}
