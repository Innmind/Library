<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HostResource;

use Domain\{
    Specification\HostResource\InResources,
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
use Innmind\Immutable\Set;
use Innmind\TimeContinuum\PointInTimeInterface;
use PHPUnit\Framework\TestCase;

class InResourcesTest extends TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $set = (new Set(ResourceIdentity::class))
            ->add($identity);
        $spec = new InResources($set);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
        $this->assertSame('resource', $spec->property());
        $this->assertSame('in', $spec->sign());
        $this->assertSame(['uuid'], $spec->value());
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidSet()
    {
        new InResources(new Set('string'));
    }

    public function testIsSatisfiedBy()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $set = (new Set(ResourceIdentity::class))
            ->add($identity);
        $spec = new InResources($set);
        $relation = new Entity(
            $this->createMock(IdentityInterface::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PointInTimeInterface::class)
        );
        $relation
            ->resource()
            ->expects($this->at(0))
            ->method('__toString')
            ->willReturn('uuid');
        $relation
            ->resource()
            ->expects($this->at(1))
            ->method('__toString')
            ->willReturn('foo');

        $this->assertTrue($spec->isSatisfiedBy($relation));
        $this->assertFalse($spec->isSatisfiedBy($relation));
    }

    public function testAnd()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $set = (new Set(ResourceIdentity::class))
            ->add($identity);
        $spec = new InResources($set);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $set = (new Set(ResourceIdentity::class))
            ->add($identity);
        $spec = new InResources($set);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $set = (new Set(ResourceIdentity::class))
            ->add($identity);
        $spec = new InResources($set);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
