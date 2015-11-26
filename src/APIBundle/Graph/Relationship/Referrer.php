<?php

namespace APIBundle\Graph\Relationship;

use APIBundle\Graph\Node\HttpResource;

class Referrer
{
    protected $uuid;
    protected $source;
    protected $destination;

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param HttpResource $resource
     *
     * @return Referrer self
     */
    public function setSource(HttpResource $resource)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return HttpResource
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param HttpResource $resource
     *
     * @return Referrer self
     */
    public function setDestination(HttpResource $resource)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @return HttpResource
     */
    public function getDestination()
    {
        return $this->destination;
    }
}
