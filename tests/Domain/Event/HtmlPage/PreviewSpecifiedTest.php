<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\PreviewSpecified,
    Entity\HtmlPage\IdentityInterface
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class PreviewSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new PreviewSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            $url = $this->createMock(UrlInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($url, $event->url());
    }
}