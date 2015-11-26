<?php

namespace APIBundle\Graph\Relationship;

use APIBundle\Graph\Node\Citation;
use APIBundle\Graph\Node\HttpResource;

class CitedIn
{
    protected $uuid;
    protected $citation;
    protected $resource;
    protected $date;

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param Citation $citation
     *
     * @return CitedIn self
     */
    public function setCitation(Citation $citation)
    {
        $this->citation = $citation;

        return $this;
    }

    /**
     * @return Citation
     */
    public function getCitation()
    {
        return $this->citation;
    }

    /**
     * @param HttpResource $resource
     *
     * @return CitedIn self
     */
    public function setAuthor(HttpResource $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return HttpResource
     */
    public function getAuthor()
    {
        return $this->resource;
    }

    /**
     * @param DateTime $date
     *
     * @return PageAuthor self
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
