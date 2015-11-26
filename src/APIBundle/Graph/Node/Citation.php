<?php

namespace APIBundle\Graph\Node;

class Citation
{
    protected $uuid;
    protected $text;

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $text
     *
     * @return Citation self
     */
    public function setText($text)
    {
        $this->text = (string) $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
