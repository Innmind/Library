<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HttpResource;

use Domain\Specification\HttpResource\Query;
use Innmind\Specification\ComparatorInterface;
use Innmind\Url\QueryInterface;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $query = $this->createMock(QueryInterface::class);
        $query
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('?foo');
        $spec = new Query($query);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('query', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('?foo', $spec->value());
    }
}
