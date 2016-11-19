<?php

namespace Jmoati\FFMpeg\Data;

class Dimension
{
    /**
     * @var integer
     */
    protected $width;

    /**
     * @var integer
     */
    protected $height;

    /**
     * @param integer $width
     * @param integer $height
     *
     * @return Dimension
     */
    public static function create($width, $height)
    {
        return new static($width, $height);
    }

    /**
     * @param string $string
     *
     * @return Dimension
     */
    public static function createFromString($string)
    {
        preg_match('/([0-9]+)\s?[:xX,;]{1}\s?([0-9]+)/', $string, $matches);

        return self::create($matches[1], $matches[2]);
    }

    /**
     * @param integer $width
     * @param integer $height
     */
    public function __construct($width, $height)
    {
        $this->width  = $width;
        $this->height = $height;
    }

    /**
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param integer $height
     * @param integer $modulo
     *
     * @return Dimension
     */
    public function setHeight($height, $modulo = 2)
    {
        $this->height = floor($height) - (floor($height) % $modulo);

        return $this;
    }

    /**
     * @param integer $width
     * @param integer $modulo
     *
     * @return Dimension
     */
    public function setWidth($width, $modulo = 2)
    {
        $this->width = floor($width) - (floor($width) % $modulo);

        return $this;
    }

    /**
     * @return integer
     */
    public function getRatio()
    {
        return $this->width / $this->height;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%sx%s', $this->width, $this->height);
    }
}
