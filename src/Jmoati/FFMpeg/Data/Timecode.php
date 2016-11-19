<?php

namespace Jmoati\FFMpeg\Data;

class Timecode
{
    /**
     * @var integer
     */
    protected $hours;

    /**
     * @var integer
     */
    protected $minutes;

    /**
     * @var integer
     */
    protected $seconds;

    /**
     * @var integer
     */
    protected $frames;

    /**
     * @param integer $frames
     * @param integer $seconds
     * @param integer $minutes
     * @param integer $hours
     */
    public function __construct($frames = 0, $seconds = 0, $minutes = 0, $hours = 0)
    {
        $this->frames  = $frames;
        $this->seconds = $seconds;
        $this->minutes = $minutes;
        $this->hours   = $hours;
    }

    /**
     * @return Timecode
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param interger $frames
     * @param double   $fps
     *
     * @return Timecode
     */
    public static function createFromFrame($frames, $fps)
    {
        return self::create()->fromFrame($frames, $fps);
    }

    /**
     * @param double $secondes
     *
     * @return Timecode
     */
    public static function createFromSeconds($secondes)
    {
        return self::create()->fromSeconds($secondes);
    }

    /**
     * @param string $string
     *
     * @return Timecode
     */
    public static function createFromString($string)
    {
        return self::create()->fromString($string);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%02d:%02d:%02d.%02d', $this->hours, $this->minutes, $this->seconds, $this->frames);
    }

    /**
     * @param integer $frames
     * @param double  $fps
     *
     * @return Timecode
     */
    public function fromFrame($frames, $fps)
    {
        return $this->fromSeconds($frames / $fps);
    }

    /**
     * @param double $seconds
     *
     * @return Timecode
     */
    public function fromSeconds($seconds)
    {
        $left          = floor($seconds);
        $this->frames  = round(100 * ($seconds - $left));
        $this->seconds = $left % 60;

        $left = ($left - $this->seconds) / 60;

        $this->minutes = $left % 60;
        $this->hours   = ($left - $this->minutes) / 60;

        return $this;
    }

    /**
     * @param string $string
     *
     * @return Timecode
     */
    public function fromString($string)
    {
        preg_match('/^([0-9]+):([0-9]+):([0-9]+)[:,\.]{1}([0-9]+)$/', $string, $matches);

        $this->hours   = $matches[1];
        $this->minutes = $matches[2];
        $this->seconds = $matches[3];
        $this->frames  = $matches[4];

        return $this;
    }
}
