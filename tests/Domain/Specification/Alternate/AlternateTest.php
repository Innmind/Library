<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Alternate;

use Domain\{
    Specification\Alternate\Alternate,
    Entity\HttpResource\IdentityInterface
};
use Innmind\Specification\ComparatorInterface;

class AlternateTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(IdentityInterface::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Alternate($identity);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('alternate', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('uuid', $spec->value());
    }
}
