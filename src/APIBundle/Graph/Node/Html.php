<?php

namespace APIBundle\Graph\Node;

class Html extends HttpResource
{
    protected $content;
    protected $description;
    protected $anchors;
    protected $journal;
    protected $language;
    protected $themeColor;
    protected $title;
    protected $rss;
    protected $android;
    protected $ios;

    /**
     * @param string $content
     *
     * @return Html self
     */
    public function setContent($content)
    {
        $this->content = (string) $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $description
     *
     * @return Html self
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
     * @param array $anchors
     *
     * @return Html self
     */
    public function setAnchors(array $anchors)
    {
        $this->anchors = $anchors;

        return $this;
    }

    /**
     * @param bool $flag
     *
     * @return Html self
     */
    public function setJournal($flag)
    {
        $this->journal = (bool) $flag;

        return $this;
    }

    /**
     * @return bool
     */
    public function isJournal()
    {
        return $this->journal;
    }

    /**
     * @param string $lang
     *
     * @return Html self
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
     * @param array $themeColor
     *
     * @return Html self
     */
    public function setThemeColor(array $themeColor)
    {
        $this->themeColor = $themeColor;

        return $this;
    }

    /**
     * @return array
     */
    public function getThemeColor()
    {
        return $this->themeColor;
    }

    /**
     * @param string $title
     *
     * @return Html self
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $rss
     *
     * @return Html self
     */
    public function setRss($rss)
    {
        $this->rss = (string) $rss;

        return $this;
    }

    /**
     * @return string
     */
    public function getRss()
    {
        return $this->rss;
    }

    /**
     * @return array
     */
    public function getAnchors()
    {
        return $this->anchors;
    }

    /**
     * @param string $android
     *
     * @return Html self
     */
    public function setAndroid($android)
    {
        $this->android = (string) $android;

        return $this;
    }

    /**
     * @return string
     */
    public function getAndroid()
    {
        return $this->android;
    }

    /**
     * @param string $ios
     *
     * @return Html self
     */
    public function setIos($ios)
    {
        $this->ios = (string) $ios;

        return $this;
    }

    /**
     * @return string
     */
    public function getIos()
    {
        return $this->ios;
    }
}
