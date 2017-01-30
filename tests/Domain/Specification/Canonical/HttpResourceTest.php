<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Canonical;

use Domain\{
    Specification\Canonical\HttpResource,
    Specification\Canonical\SpecificationInterface,
    Specification\Canonical\AndSpecification,
    Specification\Canonical\OrSpecification,
    Specification\Canonical\Not,
    Entity\Canonical as Entity,
    Entity\Canonical\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\Specification\ComparatorInterface;
use Innmind\TimeContinuum\PointInTimeInterface;

class HttpResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new HttpResource($identity);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
        $this->assertSame('resource', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('uuid', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new HttpResource($identity);
        $canonical = new Entity(
            $this->createMock(IdentityInterface::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PointInTimeInterface::class)
        );
        $canonical
            ->resource()
            ->expects($this->at(0))
            ->method('__toString')
            ->willReturn('uuid');
        $canonical
            ->resource()
            ->expects($this->at(1))
            ->method('__toString')
            ->willReturn('foo');

        $this->assertTrue($spec->isSatisfiedBy($canonical));
        $this->assertFalse($spec->isSatisfiedBy($canonical));
    }

    public function testAnd()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new HttpResource($identity);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new HttpResource($identity);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new HttpResource($identity);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
