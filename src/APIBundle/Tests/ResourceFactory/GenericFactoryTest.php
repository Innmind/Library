<?php

namespace APIBundle\Tests\ResourceFactory;

use APIBundle\ResourceFactory\GenericFactory;
use APIBundle\Graph\Node\HttpResource;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Rest\Server\Definition\ResourceDefinition;
use Symfony\Component\PropertyAccess\PropertyAccess;

class GenericFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $f;

    public function setUp()
    {
        $this->f = new GenericFactory(
            $em = $this->getMock(EntityManagerInterface::class),
            PropertyAccess::createPropertyAccessor()
        );

        $em
            ->method('contains')
            ->will($this->returnCallback(function($entity) {
                return !$entity instanceof \stdClass;
            }));
    }

    public function testSupports()
    {
        $this->assertFalse($this->f->supports(
            new \stdClass,
            new ResourceDefinition('foo')
        ));
        $this->assertTrue($this->f->supports(
            new HttpResource,
            new ResourceDefinition('foo')
        ));
    }
}
