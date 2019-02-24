<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification;

use Domain\Specification\AndSpecification;
use Innmind\Specification\{
    Specification,
    Composite,
    Operator,
};
use PHPUnit\Framework\TestCase;

class AndSpecificationTest extends TestCase
{
    public function testInterface()
    {
        $and = new AndSpecification(
            $left = $this->createMock(Specification::class),
            $right = $this->createMock(Specification::class)
        );

        $this->assertInstanceOf(Composite::class, $and);
        $this->assertSame($left, $and->left());
        $this->assertSame($right, $and->right());
        $this->assertEquals(Operator::and(), $and->operator());
    }
}
