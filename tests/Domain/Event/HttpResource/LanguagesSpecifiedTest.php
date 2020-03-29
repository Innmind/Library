<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HttpResource;

use Domain\{
    Event\HttpResource\LanguagesSpecified,
    Entity\HttpResource\Identity,
    Model\Language
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class LanguagesSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new LanguagesSpecified(
            $identity = $this->createMock(Identity::class),
            $languages = Set::of(Language::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($languages, $event->languages());
    }

    public function testThrowWhenInvalidSetOfLanguages()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 2 must be of type Set<Domain\Model\Language>');

        new LanguagesSpecified(
            $this->createMock(Identity::class),
            Set::of('string')
        );
    }
}
