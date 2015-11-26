<?php

namespace APIBundle\Graph\Node;

class HttpResource
{
    protected $uuid;
    protected $scheme;
    protected $port;
    protected $path;
    protected $query;
    protected $charset;

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $scheme
     *
     * @return HttpResource self
     */
    public function setScheme($scheme)
    {
        $this->scheme = (string) $scheme;

        return $this;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param int $port
     *
     * @return HttpResource self
     */
    public function setPort($port)
    {
        $this->port = (int) $port;

        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $path
     *
     * @return HttpResource self
     */
    public function setPath($path)
    {
        $this->path = (string) $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $query
     *
     * @return HttpResource self
     */
    public function setQuery($query)
    {
        $this->query = (string) $query;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $charset
     *
     * @return HttpResource self
     */
    public function setCharset($charset)
    {
        $this->charset = (string) $charset;

        return $this;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }
}
