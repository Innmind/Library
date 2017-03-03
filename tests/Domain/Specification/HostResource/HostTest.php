<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HostResource;

use Domain\{
    Specification\HostResource\Host,
    Specification\HostResource\SpecificationInterface,
    Specification\HostResource\AndSpecification,
    Specification\HostResource\OrSpecification,
    Specification\HostResource\Not,
    Entity\HostResource as Entity,
    Entity\HostResource\IdentityInterface,
    Entity\Host\IdentityInterface as HostIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\Specification\ComparatorInterface;
use Innmind\TimeContinuum\PointInTimeInterface;
use PHPUnit\Framework\TestCase;

class HostTest extends TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(HostIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Host($identity);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
        $this->assertSame('host', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('uuid', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $identity = $this->createMock(HostIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Host($identity);
        $relation = new Entity(
            $this->createMock(IdentityInterface::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PointInTimeInterface::class)
        );
        $relation
            ->host()
            ->expects($this->at(0))
            ->method('__toString')
            ->willReturn('uuid');
        $relation
            ->host()
            ->expects($this->at(1))
            ->method('__toString')
            ->willReturn('foo');

        $this->assertTrue($spec->isSatisfiedBy($relation));
        $this->assertFalse($spec->isSatisfiedBy($relation));
    }

    public function testAnd()
    {
        $identity = $this->createMock(HostIdentity::class);
        $spec = new Host($identity);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $identity = $this->createMock(HostIdentity::class);
        $spec = new Host($identity);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $identity = $this->createMock(HostIdentity::class);
        $spec = new Host($identity);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
