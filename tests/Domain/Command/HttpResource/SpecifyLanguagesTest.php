<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HttpResource;

use Domain\{
    Command\HttpResource\SpecifyLanguages,
    Entity\HttpResource\Identity,
    Model\Language,
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class SpecifyLanguagesTest extends TestCase
{
    public function testInterface()
    {
        $command = new SpecifyLanguages(
            $identity = $this->createMock(Identity::class),
            $languages = (new Set(Language::class))
                ->add(new Language('fr'))
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($languages, $command->languages());
    }

    public function testThrowWhenInvalidSetOfLanguages()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 2 must be of type SetInterface<Domain\Model\Language>');

        new SpecifyLanguages(
            $this->createMock(Identity::class),
            new Set('string')
        );
    }
}
