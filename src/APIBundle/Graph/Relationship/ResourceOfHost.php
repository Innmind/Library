<?php

namespace APIBundle\Graph\Relationship;

use APIBundle\Graph\Node\Host;
use APIBundle\Graph\Node\HttpResource;

class ResourceOfHost
{
    protected $uuid;
    protected $resource;
    protected $host;
    protected $date;

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
     * @return ResourceOfHost self
     */
    public function setResource(HttpResource $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return HttpResource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param Host $host
     *
     * @return ResourceOfHost self
     */
    public function setHost(Host $host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return Host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set the date at wich the resource has been found on the host
     *
     * @param DateTime $date
     *
     * @return ResourceOfHost self
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
