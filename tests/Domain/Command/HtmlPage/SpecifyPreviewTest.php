<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyPreview,
    Entity\HtmlPage\Identity
};
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class SpecifyPreviewTest extends TestCase
{
    public function testInterface()
    {
        $command = new SpecifyPreview(
            $identity = $this->createMock(Identity::class),
            $url = Url::of('example.com')
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($url, $command->url());
    }
}
