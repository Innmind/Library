<?php

namespace APIBundle\Graph\Relationship;

use APIBundle\Graph\Node\HttpResource;

class Referrer implements TargetableInterface
{
    protected $uuid;
    protected $source;
    protected $destination;

    /**
     * Non mapped property used to publish a message in the queue so this
     * url can be crawled
     */
    protected $url;

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param HttpResource $source
     *
     * @return Referrer self
     */
    public function setSource(HttpResource $source)
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
     * @param HttpResource $destination
     *
     * @return Referrer self
     */
    public function setDestination(HttpResource $destination)
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

    /**
     * {@inheritdoc}
     */
    public function setTarget(HttpResource $target)
    {
        return $this->setDestination($target);
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget()
    {
        return $this->getDestination();
    }

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
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->url;
    }
}