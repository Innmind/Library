<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification;

use Domain\Specification\Not;
use Innmind\Specification\{
    SpecificationInterface,
    NotInterface
};

class NotTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $not = new Not(
            $spec = $this->createMock(SpecificationInterface::class)
        );

        $this->assertInstanceOf(NotInterface::class, $not);
        $this->assertSame($spec, $not->specification());
    }
}
