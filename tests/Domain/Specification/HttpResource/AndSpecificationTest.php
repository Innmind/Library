<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HttpResource;

use Domain\{
    Specification\HttpResource\AndSpecification,
    Specification\HttpResource\SpecificationInterface,
    Specification\AndSpecification as ParentSpec,
    Entity\HttpResource,
    Entity\HttpResource\IdentityInterface
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

class AndSpecificationTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new AndSpecification(
            $this->createMock(SpecificationInterface::class),
            $this->createMock(SpecificationInterface::class)
        );

        $this->assertInstanceOf(ParentSpec::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
    }

    public function testIsSatisfiedBy()
    {
        $spec = new AndSpecification(
            $this->createMock(SpecificationInterface::class),
            $this->createMock(SpecificationInterface::class)
        );
        $resource = new HttpResource(
            $this->createMock(IdentityInterface::class),
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
