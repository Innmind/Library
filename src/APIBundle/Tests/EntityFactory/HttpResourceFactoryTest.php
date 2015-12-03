<?php

namespace APIBundle\Tests\EntityFactory;

use APIBundle\EntityFactory\HttpResourceFactory;
use APIBundle\EntityFactory\HostFactory;
use APIBundle\EntityFactory\DomainFactory;
use APIBundle\EntityFactory\AlternatesFactory;
use APIBundle\EntityFactory\CanonicalFactory;
use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\HttpResource;
use Innmind\Rest\Server\HttpResource as ServerResource;
use Innmind\Rest\Server\Definition\ResourceDefinition as Definition;
use Symfony\Component\EventDispatcher\EventDispatcher;

class HttpResourceFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $f;

    public function setUp()
    {
        $this->f = new HttpResourceFactory(
            new HostFactory(
                $d = new EventDispatcher,
                new DomainFactory($d)
            ),
            new AlternatesFactory($d),
            new CanonicalFactory($d)
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(EntityFactoryInterface::class, $this->f);
    }

    public function testSupports()
    {
        $r = new ServerResource;
        $r->setDefinition(new Definition('foo'));

        $this->assertFalse($this->f->supports($r));
        $r->getDefinition()->addOption('class', '');
        $this->assertFalse($this->f->supports($r));
        $r->getDefinition()->addOption('class', HttpResource::class);
        $this->assertTrue($this->f->supports($r));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Expecting an entity of type APIBundle\Graph\Node\HttpResource
     */
    public function testThrowWhenGivingInvalidEntity()
    {
        $this->f->build(new ServerResource, '');
    }

    public function testBuild()
    {
        $r = new ServerResource;
        $r
            ->set('scheme', 'https')
            ->set('port', '80')
            ->set('path', 'path')
            ->set('query', 'query')
            ->set('charset', 'charset');
        $e = new HttpResource;
        $this->assertSame(null, $this->f->build($r, $e));
        $this->assertSame('https', $e->getScheme());
        $this->assertSame(80, $e->getPort());
        $this->assertSame('path', $e->getPath());
        $this->assertSame('query', $e->getQuery());
        $this->assertSame('charset', $e->getCharset());
    }
}
