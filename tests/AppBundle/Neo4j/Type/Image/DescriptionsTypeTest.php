<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Neo4j\Type\Image;

use AppBundle\Neo4j\Type\Image\DescriptionsType;
use Domain\Entity\Image\Description;
use Innmind\Neo4j\ONM\TypeInterface;
use Innmind\Immutable\{
    SetInterface,
    Set,
    CollectionInterface,
    Collection
};

class DescriptionsTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            TypeInterface::class,
            new DescriptionsType
        );
    }

    public function testIdentifiers()
    {
        $this->assertInstanceOf(
            SetInterface::class,
            DescriptionsType::identifiers()
        );
        $this->assertSame('string', (string) DescriptionsType::identifiers()->type());
        $this->assertSame(DescriptionsType::identifiers(), DescriptionsType::identifiers());
        $this->assertSame(
            ['image_descriptions'],
            DescriptionsType::identifiers()->toPrimitive()
        );
    }

    public function testFromConfig()
    {
        $this->assertInstanceOf(
            DescriptionsType::class,
            DescriptionsType::fromConfig(
                $this->createMock(CollectionInterface::class)
            )
        );
    }

    public function testForDatabase()
    {
        $this->assertSame(
            [],
            (new DescriptionsType)->forDatabase(new Set(Description::class))
        );
        $this->assertSame(
            ['foo', 'bar'],
            (new DescriptionsType)->forDatabase(
                (new Set(Description::class))
                    ->add(new Description('foo'))
                    ->add(new Description('bar'))
            )
        );
    }

    public function testFromDatabase()
    {
        $set = (new DescriptionsType)->fromDatabase(['foo', 'bar']);
        $this->assertInstanceOf(SetInterface::class, $set);
        $this->assertSame(Description::class, (string) $set->type());
        $this->assertSame('foo', (string) $set->current());
        $set->next();
        $this->assertSame('bar', (string) $set->current());
    }

    public function testIsNullable()
    {
        $this->assertFalse((new DescriptionsType)->isNullable());
    }
}
