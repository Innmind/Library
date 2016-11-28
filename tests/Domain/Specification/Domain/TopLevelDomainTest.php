<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Domain;

use Domain\Specification\Domain\TopLevelDomain;
use Innmind\Specification\ComparatorInterface;

class TopLevelDomainTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new TopLevelDomain('foo');

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('tld', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('foo', $spec->value());
    }
}
