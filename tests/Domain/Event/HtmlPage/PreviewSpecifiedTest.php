<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\PreviewSpecified,
    Entity\HtmlPage\Identity
};
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class PreviewSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new PreviewSpecified(
            $identity = $this->createMock(Identity::class),
            $url = Url::of('example.com')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($url, $event->url());
    }
}
