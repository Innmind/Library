<?php

namespace APIBundle\Graph\Node;

/**
 * A host belongs to a domain
 *
 * Example: the host www.example.com belongs to the domain example.com
 */
class Host
{
    protected $uuid;
    protected $host;

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $host
     *
     * @return Host self
     */
    public function setHost($host)
    {
        $this->host = (string) $host;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }
}
