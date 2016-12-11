<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HttpResource;

use Domain\{
    Specification\HttpResource\Not,
    Specification\HttpResource\SpecificationInterface,
    Specification\Not as ParentSpec,
    Entity\HttpResource,
    Entity\HttpResource\IdentityInterface
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

class NotTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new Not(
            $this->createMock(SpecificationInterface::class)
        );

        $this->assertInstanceOf(ParentSpec::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
    }

    public function testIsSatisfiedBy()
    {
        $spec = new Not(
            $this->createMock(SpecificationInterface::class)
        );
        $resource = new HttpResource(
            $this->createMock(IdentityInterface::class),
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
