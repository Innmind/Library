<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Alternate;

use Domain\{
    Specification\Alternate\Resource,
    Entity\HttpResource\IdentityInterface
};
use Innmind\Specification\ComparatorInterface;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(IdentityInterface::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Resource($identity);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('resource', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('uuid', $spec->value());
    }
}
