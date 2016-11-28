<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HostResource;

use Domain\{
    Specification\HostResource\InResources,
    Entity\HttpResource\IdentityInterface
};
use Innmind\Specification\ComparatorInterface;
use Innmind\Immutable\Set;

class InResourcesTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(IdentityInterface::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $set = (new Set(IdentityInterface::class))
            ->add($identity);
        $spec = new InResources($set);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
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
}
