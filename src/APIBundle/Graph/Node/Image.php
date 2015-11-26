<?php

namespace APIBunle\Graph\Node;

class Image extends HttpResource
{
    protected $width;
    protected $height;
    protected $mime;
    protected $extension;
    protected $weight;
    protected $exif;

    /**
     * @param int $width
     *
     * @return Image self
     */
    public function setWidth($width)
    {
        $this->width = (int) $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $height
     *
     * @return Image self
     */
    public function setHeight($height)
    {
        $this->height = (int) $height;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $mime
     *
     * @return Image self
     */
    public function setMime($mime)
    {
        $this->mime = (string) $mime;

        return $this;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @param string $extension
     *
     * @return Image self
     */
    public function setExtension($extension)
    {
        $this->extension = (string) $extension;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param int $weight
     *
     * @return Image self
     */
    public function setWeight($weight)
    {
        $this->weight = (int) $weight;

        return $this;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param array $exif
     *
     * @return Image self
     */
    public function setWeight(array $exif)
    {
        $this->exif = $exif;

        return $this;
    }

    /**
     * @return array
     */
    public function getWeight()
    {
        return $this->exif;
    }
}
