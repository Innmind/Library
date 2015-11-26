<?php

namespace APIBundle\Graph\Relationship;

use APIBundle\Graph\Node\Host;
use APIBundle\Graph\Node\Domain;

class HostOfDomain
{
    protected $uuid;
    protected $host;
    protected $domain;

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param Host $host
     *
     * @return HostOfDomain self
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
     * @param Domain $domain
     *
     * @return HostOfDomain self
     */
    public function setDomain(Domain $domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return Domain
     */
    public function getDomain()
    {
        return $this->domain;
    }
}
