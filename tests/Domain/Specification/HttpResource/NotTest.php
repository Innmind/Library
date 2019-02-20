<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HttpResource;

use Domain\{
    Specification\HttpResource\Not,
    Specification\HttpResource\Specification,
    Specification\Not as ParentSpec,
    Entity\HttpResource,
    Entity\HttpResource\Identity,
};
use Innmind\Url\{
    PathInterface,
    QueryInterface,
};
use PHPUnit\Framework\TestCase;

class NotTest extends TestCase
{
    public function testInterface()
    {
        $spec = new Not(
            $this->createMock(Specification::class)
        );

        $this->assertInstanceOf(ParentSpec::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
    }

    public function testIsSatisfiedBy()
    {
        $spec = new Not(
            $this->createMock(Specification::class)
        );
        $resource = new HttpResource(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
        $spec
            ->specification()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($resource)
            ->willReturn(false);
        $spec
            ->specification()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($resource)
            ->willReturn(true);

        $this->assertTrue($spec->isSatisfiedBy($resource));
        $this->assertFalse($spec->isSatisfiedBy($resource));
    }
}
