<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\TitleSpecified,
    Entity\HtmlPage\IdentityInterface
};

class TitleSpecifiedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new TitleSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            $title = 'foo'
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($title, $event->title());
    }
}
