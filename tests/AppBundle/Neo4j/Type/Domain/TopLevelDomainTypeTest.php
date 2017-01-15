<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type\Domain;

use AppBundle\Neo4j\Type\Domain\TopLevelDomainType;
use Domain\Entity\Domain\TopLevelDomain;
use Innmind\Neo4j\ONM\TypeInterface;
use Innmind\Immutable\{
    SetInterface,
    CollectionInterface
};

class TopLevelDomainTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            TypeInterface::class,
            new TopLevelDomainType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            TopLevelDomainType::identifiers()
        );
        $this->assertSame('string', (string) TopLevelDomainType::identifiers()->type());
        $this->assertSame(TopLevelDomainType::identifiers(), TopLevelDomainType::identifiers());
        $this->assertSame(
            ['domain_tld'],
            TopLevelDomainType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            TopLevelDomainType::class,
            TopLevelDomainType::fromConfig(
                $this->createMock(CollectionInterface::class)
            )
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
