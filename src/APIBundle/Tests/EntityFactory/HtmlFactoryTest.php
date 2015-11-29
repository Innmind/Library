<?php

namespace APIBundle\Tests\EntityFactory;

use APIBundle\EntityFactory\HtmlFactory;
use APIBundle\EntityFactory\HttpResourceFactory;
use APIBundle\EntityFactory\ImagesFactory;
use APIBundle\EntityFactory\LinksFactory;
use APIBundle\EntityFactory\AuthorFactory;
use APIBundle\EntityFactory\CitationsFactory;
use APIBundle\EntityFactory\DomainFactory;
use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Relationship\ResourceOfHost;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResource;
use Innmind\Rest\Server\Definition\ResourceDefinition as Definition;
use Symfony\Component\EventDispatcher\EventDispatcher;

class HtmlFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $f;
    protected $d;

    public function setUp()
    {
        $this->d = new EventDispatcher;
        $this->f = new HtmlFactory(
            $this
                ->getMockBuilder(HttpResourceFactory::class)
                ->disableOriginalConstructor()
                ->getMock(),
            new ImagesFactory($this->d),
            new LinksFactory($this->d),
            new AuthorFactory($this->d),
            new CitationsFactory($this->d)
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(EntityFactoryInterface::class, $this->f);
    }

    public function testSupports()
    {
        $r = new HttpResource;
        $r->setDefinition(new Definition('foo'));

        $this->assertFalse($this->f->supports($r));
        $r->getDefinition()->addOption('class', '');
        $this->assertFalse($this->f->supports($r));
        $r->getDefinition()->addOption('class', Html::class);
        $this->assertTrue($this->f->supports($r));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Expecting an entity of type APIBundle\Graph\Node\Html
     */
    public function testThrowWhenGivingInvalidEntity()
    {
        $this->f->build(new HttpResource, '');
    }

    public function testBuild()
    {
        $r = new HttpResource;
        $r
            ->set('content', 'content')
            ->set('description', 'description')
            ->set('anchors', ['foo'])
            ->set('journal', true)
            ->set('language', 'fr')
            ->set('theme_color', [10, 10, 10])
            ->set('title', 'title')
            ->set('rss', 'rss')
            ->set('android', 'android')
            ->set('ios', 'ios');
        $e = new Html;
        $this->assertSame(null, $this->f->build($r, $e));
        $this->assertSame('content', $e->getContent());
        $this->assertSame('description', $e->getDescription());
        $this->assertSame(['foo'], $e->getAnchors());
        $this->assertSame(true, $e->isJournal());
        $this->assertSame('fr', $e->getLanguage());
        $this->assertSame([10, 10, 10], $e->getThemeColor());
        $this->assertSame('title', $e->getTitle());
        $this->assertSame('rss', $e->getRss());
        $this->assertSame('android', $e->getAndroid());
        $this->assertSame('ios', $e->getIos());
    }
}
