<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Rest\Gateway\HtmlPageGateway;

use AppBundle\Rest\Gateway\HtmlPageGateway\ResourceCreator;
use Domain\Command\{
    RegisterHtmlPage,
    RegisterDomain,
    RegisterHost,
    HttpResource\SpecifyCharset,
    HttpResource\SpecifyLanguages,
    HttpResource\RegisterAuthor as RegisterResourceAuthor,
    HtmlPage\FlagAsJournal,
    HtmlPage\SpecifyAnchors,
    HtmlPage\SpecifyAndroidAppLink,
    HtmlPage\SpecifyIosAppLink,
    HtmlPage\SpecifyDescription,
    HtmlPage\SpecifyMainContent,
    HtmlPage\SpecifyThemeColour,
    HtmlPage\SpecifyTitle,
    HtmlPage\SpecifyPreview,
    RegisterAuthor,
    RegisterCitation,
    Citation\RegisterAppearance
};
use Innmind\Rest\Server\{
    ResourceCreatorInterface,
    Definition\HttpResource as Definition,
    Definition\Identity,
    Definition\Gateway,
    Definition\Property as PropertyDefinition,
    HttpResourceInterface,
    Property
};
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Immutable\{
    Map,
    Set
};
use PHPUnit\Framework\TestCase;

class ResourceCreatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            ResourceCreatorInterface::class,
            new ResourceCreator(
                $this->createMock(CommandBusInterface::class)
            )
        );
    }

    public function testExcecution()
    {
        $expected = null;
        $creator = new ResourceCreator(
            $bus = $this->createMock(CommandBusInterface::class)
        );
        $bus
            ->expects($this->at(0))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterDomain &&
                    (string) $command->host() === 'example.com';
            }));
        $bus
            ->expects($this->at(1))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterHost &&
                    (string) $command->host() === 'example.com';
            }));
        $bus
            ->expects($this->at(2))
            ->method('handle')
            ->with($this->callback(function($command) use (&$expected): bool {
                $expected = $command->identity();

                return $command instanceof RegisterHtmlPage &&
                    (string) $command->path() === 'foo' &&
                    (string) $command->query() === 'bar';
            }));
        $bus
            ->expects($this->at(3))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyCharset &&
                    (string) $command->charset() === 'UTF-8';
            }));
        $bus
            ->expects($this->at(4))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyLanguages &&
                    $command->languages()->size() === 1 &&
                    (string) $command->languages()->current() === 'fr';
            }));
        $bus
            ->expects($this->at(5))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterAuthor &&
                    (string) $command->name() === 'author name';
            }));
        $bus
            ->expects($this->at(6))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterResourceAuthor;
            }));
        $bus
            ->expects($this->at(7))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterCitation &&
                    (string) $command->text() === 'cite';
            }));
        $bus
            ->expects($this->at(8))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterAppearance;
            }));
        $bus
            ->expects($this->at(9))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof FlagAsJournal;
            }));
        $bus
            ->expects($this->at(10))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyAnchors &&
                    $command->anchors()->size() === 1 &&
                    (string) $command->anchors()->current() === '#someAnchor';
            }));
        $bus
            ->expects($this->at(11))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyAndroidAppLink &&
                    (string) $command->url() === 'android://foo/';
            }));
        $bus
            ->expects($this->at(12))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyDescription &&
                    $command->description() === 'desc';
            }));
        $bus
            ->expects($this->at(13))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyIosAppLink &&
                    (string) $command->url() === 'ios://foo/';
            }));
        $bus
            ->expects($this->at(14))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyMainContent &&
                    $command->mainContent() === 'main content';
            }));
        $bus
            ->expects($this->at(15))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyThemeColour &&
                    (string) $command->colour() === '#3399ff';
            }));
        $bus
            ->expects($this->at(16))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyTitle &&
                    $command->title() === 'some title';
            }));
        $bus
            ->expects($this->at(17))
            ->method('handle')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyPreview &&
                    (string) $command->url() === 'http://some.photo/url';
            }));
        $definition = new Definition(
            'html_page',
            new Identity('identity'),
            new Map('string', PropertyDefinition::class),
            new Map('scalar', 'variable'),
            new Map('scalar', 'variable'),
            new Gateway('html_page'),
            false,
            new Map('string', 'string')
        );
        $resource = $this->createMock(HttpResourceInterface::class);
        $resource
            ->expects($this->at(0))
            ->method('property')
            ->with('host')
            ->willReturn(new Property('host', 'example.com'));
        $resource
            ->expects($this->at(1))
            ->method('property')
            ->with('path')
            ->willReturn(new Property('path', 'foo'));
        $resource
            ->expects($this->at(2))
            ->method('property')
            ->with('query')
            ->willReturn(new Property('query', 'bar'));
        $resource
            ->expects($this->at(3))
            ->method('has')
            ->with('charset')
            ->willReturn(true);
        $resource
            ->expects($this->at(4))
            ->method('property')
            ->with('charset')
            ->willReturn(new Property('charset', 'UTF-8'));
        $resource
            ->expects($this->at(5))
            ->method('has')
            ->with('languages')
            ->willReturn(true);
        $resource
            ->expects($this->at(6))
            ->method('property')
            ->with('languages')
            ->willReturn(new Property('languages', (new Set('string'))->add('fr')));
        $resource
            ->expects($this->at(7))
            ->method('has')
            ->with('author')
            ->willReturn(true);
        $resource
            ->expects($this->at(8))
            ->method('property')
            ->with('author')
            ->willReturn(new Property('author', 'author name'));
        $resource
            ->expects($this->at(9))
            ->method('has')
            ->with('citations')
            ->willReturn(true);
        $resource
            ->expects($this->at(10))
            ->method('property')
            ->with('citations')
            ->willReturn(new Property('citations', (new Set('string'))->add('cite')));
        $resource
            ->expects($this->at(11))
            ->method('has')
            ->with('is_journal')
            ->willReturn(true);
        $resource
            ->expects($this->at(12))
            ->method('has')
            ->with('anchors')
            ->willReturn(true);
        $resource
            ->expects($this->at(13))
            ->method('property')
            ->with('anchors')
            ->willReturn(new Property('anchors', (new Set('string'))->add('someAnchor')));
        $resource
            ->expects($this->at(14))
            ->method('has')
            ->with('android_app_link')
            ->willReturn(true);
        $resource
            ->expects($this->at(15))
            ->method('property')
            ->with('android_app_link')
            ->willReturn(new Property('android_app_link', 'android://foo/'));
        $resource
            ->expects($this->at(16))
            ->method('has')
            ->with('description')
            ->willReturn(true);
        $resource
            ->expects($this->at(17))
            ->method('property')
            ->with('description')
            ->willReturn(new Property('description', 'desc'));
        $resource
            ->expects($this->at(18))
            ->method('has')
            ->with('ios_app_link')
            ->willReturn(true);
        $resource
            ->expects($this->at(19))
            ->method('property')
            ->with('ios_app_link')
            ->willReturn(new Property('ios_app_link', 'ios://foo/'));
        $resource
            ->expects($this->at(20))
            ->method('has')
            ->with('main_content')
            ->willReturn(true);
        $resource
            ->expects($this->at(21))
            ->method('property')
            ->with('main_content')
            ->willReturn(new Property('main_content', 'main content'));
        $resource
            ->expects($this->at(22))
            ->method('has')
            ->with('theme_colour')
            ->willReturn(true);
        $resource
            ->expects($this->at(23))
            ->method('property')
            ->with('theme_colour')
            ->willReturn(new Property('theme_colour', '39F'));
        $resource
            ->expects($this->at(24))
            ->method('has')
            ->with('title')
            ->willReturn(true);
        $resource
            ->expects($this->at(25))
            ->method('property')
            ->with('title')
            ->willReturn(new Property('title', 'some title'));
        $resource
            ->expects($this->at(26))
            ->method('has')
            ->with('preview')
            ->willReturn(true);
        $resource
            ->expects($this->at(27))
            ->method('property')
            ->with('preview')
            ->willReturn(new Property('preview', 'http://some.photo/url'));

        $identity = $creator($definition, $resource);

        $this->assertSame($expected, $identity);
    }
}
