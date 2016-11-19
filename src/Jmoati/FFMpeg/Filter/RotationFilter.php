<?php

namespace Jmoati\FFMpeg\Filter;

class RotationFilter extends AbstractFilter implements InterfaceFormatFilter, InterfaceFrameFilter
{
    const ROTATION_90  = 'transpose=1';
    const ROTATION_180 = 'transpose=1, transpose=1';
    const ROTATION_270 = 'transpose=2';

    /**
     * @var string
     */
    protected $rotation;

    /**
     * @param string $rotation
     */
    public function __construct($rotation)
    {
        $this->rotation = $rotation;
    }

    /**
     * @return string
     */
    public function getRotation()
    {
        return $this->rotation;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('-vf "%s"', $this->rotation);
    }
}
