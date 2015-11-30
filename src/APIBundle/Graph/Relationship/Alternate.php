<?php

namespace APIBundle\Graph\Relationship;

use APIBundle\Graph\Node\HttpResource;

class Alternate implements TargetableInterface
{
    protected $uuid;
    protected $source;
    protected $destination;
    protected $date;
    protected $language;

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
     * @return Alternate self
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
     * @param HttpResource $destination
     *
     * @return Alternate self
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
     * Set the date at which the alternate link was found
     *
     * @param DateTime $date
     *
     * @return Alternate self
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

    /**
     * Set the language the destination resource is written in
     *
     * @param string $lang
     *
     * @return Alternate self
     */
    public function setLanguage($lang)
    {
        $this->language = (string) $lang;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
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
