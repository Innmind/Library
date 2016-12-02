<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\CitationAppearance;

use Domain\{
    Specification\CitationAppearance\Citation,
    Entity\Citation\IdentityInterface
};
use Innmind\Specification\ComparatorInterface;

class CitationTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(IdentityInterface::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Citation($identity);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('citation', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('uuid', $spec->value());
    }
}
