<?php

namespace APIBundle\Graph\Relationship;

trait UrlableTrait
{
    protected $url;

    /**
     * @param string $url
     *
     * @return Alternate self
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;

        return $this;
    }

    /**
     * Return the url designating the target resource
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Check if a url is set for crawling
     *
     * @return bool
     */
    public function hasUrl()
    {
        return !empty($this->url);
    }

    /**
     * Remove a url from the relationship, so it won't be crawled
     *
     * @return TargetableInterface self
     */
    public function removeUrl()
    {
        $this->url = null;

        return $this;
    }
}
