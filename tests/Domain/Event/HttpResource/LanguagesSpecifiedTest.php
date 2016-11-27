<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HttpResource;

use Domain\{
    Event\HttpResource\LanguagesSpecified,
    Entity\HttpResource\IdentityInterface
};
use Innmind\Immutable\SetInterface;

class LanguagesSpecifiedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new LanguagesSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            $languages = $this->createMock(SetInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($languages, $event->languages());
    }
}
