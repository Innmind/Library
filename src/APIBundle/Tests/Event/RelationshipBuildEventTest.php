<?php

namespace APIBundle\Tests\Event;

use APIBundle\Event\RelationshipBuildEvent;

class RelationshipBuildEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetter()
    {
        $e = new RelationshipBuildEvent($r = new \stdClass);

        $this->assertSame($r, $e->getRelationship());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Relationship must be an object
     */
    public function testThrowWhenGivingInvalidRelationship()
    {
        new RelationshipBuildEvent('');
    }
}
