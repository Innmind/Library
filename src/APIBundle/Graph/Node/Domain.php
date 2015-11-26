<?php

namespace APIBundle\Graph\Node;

class Domain
{
    protected $uuid;
    protected $domain;
    protected $tld;

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set the domain
     *
     * @param string $domain
     *
     * @return Domain self
     */
    public function setDomain($domain)
    {
        $this->domain = (string) $domain;

        return $this;
    }

    /**
     * Return the domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set the top level domain
     *
     * @param string $tld
     *
     * @return Domain self
     */
    public function setTld($tld)
    {
        $this->tld = (string) $tld;

        return $this;
    }

    /**
     * Return the top level domain
     *
     * @return string
     */
    public function getTld()
    {
        return $this->tld;
    }
}
