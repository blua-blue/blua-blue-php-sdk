<?php

namespace BluaBlue;

class Image
{

    private string $id;
    private string $format;
    private string $path;
    private string $inserter_user_id;

    use Helper;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param mixed $format
     */
    public function setFormat(string $format): Image
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath(string $path): Image
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getInserterUserId()
    {
        return $this->inserter_user_id;
    }

}