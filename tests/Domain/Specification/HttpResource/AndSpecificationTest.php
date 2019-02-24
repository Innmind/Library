<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HttpResource;

use Domain\{
    Specification\HttpResource\AndSpecification,
    Specification\HttpResource\Specification,
    Specification\AndSpecification as ParentSpec,
    Entity\HttpResource,
    Entity\HttpResource\Identity,
};
use Innmind\Url\{
    PathInterface,
    QueryInterface,
};
use PHPUnit\Framework\TestCase;

class AndSpecificationTest extends TestCase
{
    public function testInterface()
    {
        $spec = new AndSpecification(
            $this->createMock(Specification::class),
            $this->createMock(Specification::class)
        );

        $this->assertInstanceOf(ParentSpec::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
    }

    public function testIsSatisfiedBy()
    {
        $spec = new AndSpecification(
            $this->createMock(Specification::class),
            $this->createMock(Specification::class)
        );
        $resource = new HttpResource(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
        $spec
            ->left()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($resource)
            ->willReturn(false);
        $spec
            ->left()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($resource)
            ->willReturn(true);
        $spec
            ->right()
            ->expects($this->once())
            ->method('isSatisfiedBy')
            ->with($resource)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($resource));
        $this->assertTrue($spec->isSatisfiedBy($resource));
    }
}
