<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway\HtmlPageGateway;

use Web\Gateway\HtmlPageGateway\ResourceCreator;
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
    Citation\RegisterAppearance,
};
use Innmind\Rest\Server\{
    ResourceCreator as ResourceCreatorInterface,
    Definition\HttpResource as Definition,
    Definition\Identity,
    Definition\Gateway,
    Definition\Property as PropertyDefinition,
    HttpResource,
    HttpResource\Property,
};
use Innmind\CommandBus\CommandBus;
use Innmind\Immutable\{
    Map,
    Set,
};
use function Innmind\Immutable\first;
use PHPUnit\Framework\TestCase;

class ResourceCreatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            ResourceCreatorInterface::class,
            new ResourceCreator(
                $this->createMock(CommandBus::class)
            )
        );
    }

    public function testExcecution()
    {
        $expected = null;
        $creator = new ResourceCreator(
            $bus = $this->createMock(CommandBus::class)
        );
        $bus
            ->expects($this->at(0))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterDomain &&
                    $command->host()->toString() === 'example.com';
            }));
        $bus
            ->expects($this->at(1))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterHost &&
                    $command->host()->toString() === 'example.com';
            }));
        $bus
            ->expects($this->at(2))
            ->method('__invoke')
            ->with($this->callback(function($command) use (&$expected): bool {
                $expected = $command->identity();

                return $command instanceof RegisterHtmlPage &&
                    $command->path()->toString() === 'foo' &&
                    $command->query()->toString() === 'bar';
            }));
        $bus
            ->expects($this->at(3))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyCharset &&
                    (string) $command->charset() === 'UTF-8';
            }));
        $bus
            ->expects($this->at(4))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyLanguages &&
                    $command->languages()->size() === 1 &&
                    (string) first($command->languages()) === 'fr';
            }));
        $bus
            ->expects($this->at(5))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterAuthor &&
                    (string) $command->name() === 'author name';
            }));
        $bus
            ->expects($this->at(6))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterResourceAuthor;
            }));
        $bus
            ->expects($this->at(7))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterCitation &&
                    (string) $command->text() === 'cite';
            }));
        $bus
            ->expects($this->at(8))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterAppearance;
            }));
        $bus
            ->expects($this->at(9))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof FlagAsJournal;
            }));
        $bus
            ->expects($this->at(10))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyAnchors &&
                    $command->anchors()->size() === 1 &&
                    (string) first($command->anchors()) === '#someAnchor';
            }));
        $bus
            ->expects($this->at(11))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyAndroidAppLink &&
                    $command->url()->toString() === 'android://foo/';
            }));
        $bus
            ->expects($this->at(12))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyDescription &&
                    $command->description() === 'desc';
            }));
        $bus
            ->expects($this->at(13))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyIosAppLink &&
                    $command->url()->toString() === 'ios://foo/';
            }));
        $bus
            ->expects($this->at(14))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyMainContent &&
                    $command->mainContent() === 'main content';
            }));
        $bus
            ->expects($this->at(15))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyThemeColour &&
                    $command->colour()->toString() === '#3399ff';
            }));
        $bus
            ->expects($this->at(16))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyTitle &&
                    $command->title() === 'some title';
            }));
        $bus
            ->expects($this->at(17))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyPreview &&
                    $command->url()->toString() === 'http://some.photo/url';
            }));
        $definition = new Definition(
            'html_page',
            new Gateway('html_page'),
            new Identity('identity'),
            Set::of(PropertyDefinition::class)
        );
        $resource = $this->createMock(HttpResource::class);
        $resource
            ->expects($this->at(0))
            ->method('property')
            ->with('host')
            ->willReturn(new Property('host', 'example.com'));
        $resource
            ->expects($this->at(1))
            ->method('property')
            ->with('query')
            ->willReturn(new Property('query', 'bar'));
        $resource
            ->expects($this->at(2))
            ->method('property')
            ->with('path')
            ->willReturn(new Property('path', 'foo'));
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
            ->willReturn(new Property('languages', Set::strings('fr')));
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
            ->willReturn(new Property('citations', Set::strings('cite')));
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
            ->willReturn(new Property('anchors', Set::strings('someAnchor')));
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
