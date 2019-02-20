<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HostResource;

use Domain\{
    Specification\HostResource\InResources,
    Specification\HostResource\Specification,
    Specification\HostResource\AndSpecification,
    Specification\HostResource\OrSpecification,
    Specification\HostResource\Not,
    Entity\HostResource as Entity,
    Entity\HostResource\Identity,
    Entity\Host\Identity as HostIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Exception\InvalidArgumentException,
};
use Innmind\Specification\Comparator;
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

        $this->assertInstanceOf(Comparator::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('resource', $spec->property());
        $this->assertSame('IN', (string) $spec->sign());
        $this->assertSame(['uuid'], $spec->value());
    }

    public function testThrowWhenInvalidSet()
    {
        $this->expectException(InvalidArgumentException::class);

        new InResources(new Set('string'));
    }

    public function testIsSatisfiedBy()
    {
        $identity1 = $this->createMock(ResourceIdentity::class);
        $identity1
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $identity2 = $this->createMock(ResourceIdentity::class);
        $identity2
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('0');
        $set = (new Set(ResourceIdentity::class))
            ->add($identity1)
            ->add($identity2);
        $spec = new InResources($set);
        $relation = new Entity(
            $this->createMock(Identity::class),
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
            ->willReturn('');

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
