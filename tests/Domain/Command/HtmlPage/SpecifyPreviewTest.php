<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyPreview,
    Entity\HtmlPage\IdentityInterface
};
use Innmind\Url\UrlInterface;

class SpecifyPreviewTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new SpecifyPreview(
            $identity = $this->createMock(IdentityInterface::class),
            $url = $this->createMock(UrlInterface::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($url, $command->url());
    }
}
