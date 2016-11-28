<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\SpecifyHttpResourceLanguages,
    Entity\HttpResource\IdentityInterface
};
use Innmind\Immutable\Set;

class SpecifyHttpResourceLanguagesTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new SpecifyHttpResourceLanguages(
            $identity = $this->createMock(IdentityInterface::class),
            $languages = (new Set('string'))->add('fr')
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($languages, $command->languages());
    }
}
