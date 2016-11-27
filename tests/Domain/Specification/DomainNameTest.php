<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification;

use Domain\Specification\DomainName;
use Innmind\Specification\ComparatorInterface;

class DomainNameTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new DomainName('foo');

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('name', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('foo', $spec->value());
    }
}
