<?php

namespace APIBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class RelationshipBuildEvent extends Event
{
    protected $relationship;

    public function __construct($relationship)
    {
        if (!is_object($relationship)) {
            throw new \InvalidArgumentException(
                'Relationship must be an object'
            );
        }

        $this->relationship = $relationship;
    }

    /**
     * Return the relationship that has been built
     *
     * @return object
     */
    public function getRelationship()
    {
        return $this->relationship;
    }
}
