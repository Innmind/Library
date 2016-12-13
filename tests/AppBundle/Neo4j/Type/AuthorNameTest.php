<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type;

use AppBundle\Neo4j\Type\AuthorName;
use Domain\Entity\Author\Name;
use Innmind\Neo4j\ONM\TypeInterface;
use Innmind\Immutable\{
    SetInterface,
    CollectionInterface
};

class AuthorNameTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            TypeInterface::class,
            new AuthorName
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            AuthorName::identifiers()
        );
        $this->assertSame('string', (string) AuthorName::identifiers()->type());
        $this->assertSame(AuthorName::identifiers(), AuthorName::identifiers());
        $this->assertSame(
            ['author_name'],
            AuthorName::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            AuthorName::class,
            AuthorName::fromConfig(
                $this->createMock(CollectionInterface::class)
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            'foo',
            (new AuthorName)->forDatabase(new Name('foo'))
        );
    }

    public function testFromDatabase()
    {
        $this->assertInstanceOf(
            Name::class,
            (new AuthorName)->fromDatabase('foo')
        );
        $this->assertSame(
            'foo',
            (string) (new AuthorName)->fromDatabase('foo')
        );
    }
}


