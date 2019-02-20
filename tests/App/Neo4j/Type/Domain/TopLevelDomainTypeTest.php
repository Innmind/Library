<?php
declare(strict_types = 1);

namespace Tests\App\Neo4j\Type\Domain;

use App\Neo4j\Type\Domain\TopLevelDomainType;
use Domain\Entity\Domain\TopLevelDomain;
use Innmind\Neo4j\ONM\Type;
use PHPUnit\Framework\TestCase;

class TopLevelDomainTypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Type::class,
            new TopLevelDomainType
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new TopLevelDomainType)->forDatabase(new TopLevelDomain('foo'))
        );
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            TopLevelDomain::class,
            (new TopLevelDomainType)->fromDatabase('foo')
        );
        $this->assertSame(
            'foo',
            (string) (new TopLevelDomainType)->fromDatabase('foo')
        );
    }

    public function testIsNullable()
    {
        $this->assertFalse((new TopLevelDomainType)->isNullable());
    }
}
