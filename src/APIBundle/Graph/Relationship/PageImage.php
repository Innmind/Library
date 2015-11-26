<?php

namespace APIBundle\Graph\Relationship;

use APIBundle\Graph\Node\Image;
use APIBundle\Graph\Node\Html;

class PageImage
{
    protected $uuid;
    protected $page;
    protected $image;
    protected $date;

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
}
