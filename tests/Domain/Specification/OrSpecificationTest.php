<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification;

use Domain\Specification\OrSpecification;
use Innmind\Specification\{
    SpecificationInterface,
    CompositeInterface,
    Operator
};

class OrSpecificationTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $or = new OrSpecification(
            $left = $this->createMock(SpecificationInterface::class),
            $right = $this->createMock(SpecificationInterface::class)
        );

        $this->assertInstanceOf(CompositeInterface::class, $or);
        $this->assertSame($left, $or->left());
        $this->assertSame($right, $or->right());
        $this->assertSame(Operator::OR, (string) $or->operator());
    }
}
