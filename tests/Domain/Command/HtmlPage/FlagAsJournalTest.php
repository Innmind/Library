<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\FlagAsJournal,
    Entity\HtmlPage\IdentityInterface
};

class FlagAsJournalTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new FlagAsJournal(
            $identity = $this->createMock(IdentityInterface::class)
        );

        $this->assertSame($identity, $command->identity());
    }
}
