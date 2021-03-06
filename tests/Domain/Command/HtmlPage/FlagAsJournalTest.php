<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\FlagAsJournal,
    Entity\HtmlPage\Identity
};
use PHPUnit\Framework\TestCase;

class FlagAsJournalTest extends TestCase
{
    public function testInterface()
    {
        $command = new FlagAsJournal(
            $identity = $this->createMock(Identity::class)
        );

        $this->assertSame($identity, $command->identity());
    }
}
