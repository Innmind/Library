<?php

namespace APIBundle\Graph\Relationship;

use APIBundle\Graph\Node\Author;
use APIBundle\Graph\Node\Html;

class PageAuthor
{
    protected $uuid;
    protected $page;
    protected $author;
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
     * @return PageAuthor self
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
     * @param Author $author
     *
     * @return PageAuthor self
     */
    public function setAuthor(Author $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param DateTime $date
     *
     * @return PageAuthor self
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
