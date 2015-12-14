<?php

namespace APIBundle\Graph\Relationship;

use APIBundle\Graph\Node\HttpResource;

class Canonical implements TargetableInterface
{
    use UrlableTrait;

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
     * @param HttpResource $source
     *
     * @return Canonical self
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
     * {@inheritdoc}
     */
    public function setTarget(HttpResource $target)
    {
        return $this->setSource($target);
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget()
    {
        return $this->getSource();
    }

    /**
     * @param HttpResource $destination
     *
     * @return Canonical self
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
}
