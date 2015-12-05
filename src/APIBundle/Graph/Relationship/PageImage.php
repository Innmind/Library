<?php

namespace APIBundle\Graph\Relationship;

use APIBundle\Graph\Node\Image;
use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Node\HttpResource;

class PageImage implements TargetableInterface
{
    protected $uuid;
    protected $page;
    protected $image;
    protected $date;
    protected $description;

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
     * @param Html $page
     *
     * @return PageImage self
     */
    public function setPage(Html $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return Html
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param Image $image
     *
     * @return PageImage self
     */
    public function setImage(Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * {@inheritdoc}
     */
    public function setTarget(HttpResource $source)
    {
        return $this->setImage($source);
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget()
    {
        return $this->getImage();
    }

    /**
     * @param DateTime $date
     *
     * @return PageImage self
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
     * @param string $description
     *
     * @return PageImage self
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
