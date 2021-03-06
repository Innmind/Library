<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification;

use Domain\Specification\Not;
use Innmind\Specification\{
    Specification,
    Not as NotInterface,
};
use PHPUnit\Framework\TestCase;

class NotTest extends TestCase
{
    public function testInterface()
    {
        $not = new Not(
            $spec = $this->createMock(Specification::class)
        );

        $this->assertInstanceOf(NotInterface::class, $not);
        $this->assertSame($spec, $not->specification());
    }
}
