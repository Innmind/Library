<?php

namespace APIBundle\Tests\EntityFactory;

use APIBundle\EntityFactory\ImageFactory;
use APIBundle\EntityFactory\HttpResourceFactory;
use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Image;
use Innmind\Rest\Server\HttpResource;
use Innmind\Rest\Server\Definition\ResourceDefinition as Definition;

class ImageFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $f;

    public function setUp()
    {
        $this->f = new ImageFactory(
            $this
                ->getMockBuilder(HttpResourceFactory::class)
                ->disableOriginalConstructor()
                ->getMock()
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
        $r->getDefinition()->addOption('class', Image::class);
        $this->assertTrue($this->f->supports($r));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Expecting an entity of type APIBundle\Graph\Node\Image
     */
    public function testThrowWhenGivingInvalidEntity()
    {
        $this->f->build(new HttpResource, '');
    }

    public function testBuild()
    {
        $exif = ['key' => 'value'];
        $r = new HttpResource;
        $r
            ->set('width', '42')
            ->set('height', '42')
            ->set('mime', 'image/png')
            ->set('extension', '.png')
            ->set('weight', '42')
            ->set('exif', json_encode($exif));
        $e = new Image;
        $this->assertSame(null, $this->f->build($r, $e));
        $this->assertSame(42, $e->getWidth());
        $this->assertSame(42, $e->getHeight());
        $this->assertSame('image/png', $e->getMime());
        $this->assertSame('.png', $e->getExtension());
        $this->assertSame(42, $e->getWeight());
        $this->assertSame($exif, $e->getExif());
    }
}
