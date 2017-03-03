<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\FlaggedAsJournal,
    Entity\HtmlPage\IdentityInterface
};
use PHPUnit\Framework\TestCase;

class FlaggedAsJournalTest extends TestCase
{
    public function testInterface()
    {
        $event = new FlaggedAsJournal(
            $identity = $this->createMock(IdentityInterface::class)
        );

        $this->assertSame($identity, $event->identity());
    }
}
