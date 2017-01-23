<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\Image;

use Domain\{
    Command\Image\SpecifyWeight,
    Entity\Image\IdentityInterface,
    Entity\Image\Weight
};

class SpecifyWeightTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new SpecifyWeight(
            $identity = $this->createMock(IdentityInterface::class),
            $weight = new Weight(12, 21)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($weight, $command->weight());
    }
}
