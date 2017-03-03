<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HttpResource;

use Domain\{
    Event\HttpResource\LanguagesSpecified,
    Entity\HttpResource\IdentityInterface,
    Model\Language
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class LanguagesSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new LanguagesSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            $languages = new Set(Language::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($languages, $event->languages());
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidSetOfLanguages()
    {
        new LanguagesSpecified(
            $this->createMock(IdentityInterface::class),
            new Set('string')
        );
    }
}
