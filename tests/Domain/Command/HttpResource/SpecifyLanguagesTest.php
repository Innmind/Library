<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HttpResource;

use Domain\{
    Command\HttpResource\SpecifyLanguages,
    Entity\HttpResource\IdentityInterface
};
use Innmind\Immutable\Set;

class SpecifyLanguagesTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new SpecifyLanguages(
            $identity = $this->createMock(IdentityInterface::class),
            $languages = (new Set('string'))->add('fr')
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($languages, $command->languages());
    }
}
