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
            $languages = new Set(Language::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($languages, $event->languages());
    }

    /**
     * @expectedException TypeError
     * @expectedExceptionMessage Argument 2 must be of type SetInterface<Domain\Model\Language>
     */
    public function testThrowWhenInvalidSetOfLanguages()
    {
        new LanguagesSpecified(
            $this->createMock(Identity::class),
            new Set('string')
        );
    }
}
