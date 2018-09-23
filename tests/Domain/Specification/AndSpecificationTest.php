<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification;

use Domain\Specification\AndSpecification;
use Innmind\Specification\{
    SpecificationInterface,
    CompositeInterface,
    Operator
};
use PHPUnit\Framework\TestCase;

class AndSpecificationTest extends TestCase
{
    public function testInterface()
    {
        $and = new AndSpecification(
            $left = $this->createMock(SpecificationInterface::class),
            $right = $this->createMock(SpecificationInterface::class)
        );

        $this->assertInstanceOf(CompositeInterface::class, $and);
        $this->assertSame($left, $and->left());
        $this->assertSame($right, $and->right());
        $this->assertSame(Operator::AND, (string) $and->operator());
    }
}
