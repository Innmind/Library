<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification;

use Domain\Specification\OrSpecification;
use Innmind\Specification\{
    Specification,
    Composite,
    Operator,
};
use PHPUnit\Framework\TestCase;

class OrSpecificationTest extends TestCase
{
    public function testInterface()
    {
        $or = new OrSpecification(
            $left = $this->createMock(Specification::class),
            $right = $this->createMock(Specification::class)
        );

        $this->assertInstanceOf(Composite::class, $or);
        $this->assertSame($left, $or->left());
        $this->assertSame($right, $or->right());
        $this->assertEquals(Operator::or(), $or->operator());
    }
}
